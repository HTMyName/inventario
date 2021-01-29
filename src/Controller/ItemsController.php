<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\AddItemType;
use App\Form\InventarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/items")
 */
class ItemsController extends AbstractController
{
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

			if (($actualCant + $item->getCantidadInventario()) >= 0) {
				$item->setCantidadInventario($actualCant + $item->getCantidadInventario());
				$em->flush();
			}

			return $this->redirectToRoute('app_items');
		}
		return $this->render("items/add.html.twig", [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadInventario()
		]);
	}
}
