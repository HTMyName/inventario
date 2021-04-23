<?php

namespace App\Controller;

use App\Entity\Logs;
use App\Entity\Producto;
use App\Entity\System;
use App\Form\AddItemType;
use App\Form\InventarioType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/items")
 */
class ItemsController extends AbstractController
{
	private $logsOb;

	public function __construct()
	{
		$this->logsOb = new LogsController();
	}

	/**
	 * @Route("", name="app_items")
	 */
	public function index(Request $request): Response
	{
		$items = new Producto();
		$form = $this->createForm(AddItemType::class, $items);

		$form = $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$items->setGanancia($items->getPrecioV() - $items->getPrecioC());
			$items->setActive(1);
			$items->setCantidadInventario(0);
			$items->setCantidadTaller(0);
			$em->persist($items);
			$em->flush();
			$this->addFlash('success', "Producto añadido");
			return $this->redirectToRoute('app_items');
		}

		$items_data = $this->getDoctrine()->getRepository(Producto::class);
		$data = $items_data->showAllProducts();

		return $this->render('items/index.html.twig',
			['form' => $form->createView(), 'data' => $data]
		);
	}

	/**
	 * @Route("/delete/{id}", name="app_items_delete", requirements={"id"="\d+"})
	 */
	public function deleteAction($id = null)
	{
		if ($id !== null) {
			$this->getDoctrine()->getRepository(Producto::class)->deleteBy($id);
			$em = $this->getDoctrine()->getManager();
			$detalles = $id;
			$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'deleteitem', $detalles);
			$em->persist($logs);
			$em->flush();
		}
		return $this->redirectToRoute('app_items');
	}

	/**
	 * @Route("/edit/{id}", name="app_items_edit", requirements={"id"="\d+"})
	 */
	public function editAction($id = null, Request $request)
	{
		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$item = $em->getRepository(Producto::class)->find($id);
		} else {
			return $this->redirectToRoute('app_items');
		}
		if (!$item) {
			return $this->redirectToRoute('app_items');
		}

		//creando formulario
		$form = $this->createForm(AddItemType::class, $item);

		$form = $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$item->setGanancia($item->getPrecioV() - $item->getPrecioC());
			$em->persist($item);
			$em->flush();
			$this->addFlash('success', "Producto editado");
			return $this->redirectToRoute('app_items');
		}
		return $this->render("items/edit.html.twig", ['form' => $form->createView()]);
	}

	/**
	 * @Route("/add/{id}", name="app_item_transf", requirements={"id"="\d+"})
	 */
	public function addAction($id = null, Request $request)
	{
		$producto = new Producto();
		//$sin_fondos = "";

		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$item = $em->getRepository(Producto::class)->find($id);
		} else {
			return $this->redirectToRoute('app_items');
		}
		if (!$item) {
			return $this->redirectToRoute('app_items');
		}

		//creando formulario
		$form = $this->createForm(InventarioType::class, $producto);

		$form = $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$actualCant = $form->getData()->getCantidadInventario();

			if ($actualCant > 0) {
				$system = $this->getDoctrine()->getRepository(System::class)->find(1);

				if ($system->getRecuperado() >= $item->getPrecioC() * $actualCant) {

					$system->setInversion($system->getInversion() + $item->getPrecioC() * $actualCant);
					$system->setRecuperado($system->getRecuperado() - $item->getPrecioC() * $actualCant);
					$item->setCantidadInventario($actualCant + $item->getCantidadInventario());

					$detalles = $item->getMarca() . ',' . $item->getModelo() . ',' . $item->getPrecioC() . ',' . $actualCant;
					$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'additem', $detalles);
					$em->persist($logs);
					$em->flush();
					$this->addFlash('success', "Se añadieron {$actualCant} producto(s) al Inventario");
				} else {
					//$sin_fondos = "No hay suficiente fondo";
					$this->addFlash('error', "No hay fondos suficientes para añadir esa cantidad de productos");
					return $this->redirectToRoute('app_item_transf', [
						'id' => $id,
					]);
				}
			}

			return $this->redirectToRoute('app_items');
		}
		return $this->render("items/add.html.twig", [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadInventario(),
			//'sin_fondos' => $sin_fondos
		]);
	}
}
