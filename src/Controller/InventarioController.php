<?php

namespace App\Controller;

use App\Entity\Logs;
use App\Entity\Producto;
use App\Form\AddTallerType;
use App\Form\BajaType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inventario")
 */
class InventarioController extends AbstractController
{
	private $logsOb;

	public function __construct()
	{
		$this->logsOb = new LogsController();
	}
	/**
	 * @Route("", name="app_inventario")
	 */
	public function index(): Response
	{

		$items_data = $this->getDoctrine()->getRepository(Producto::class);
		$data = $items_data->showAllIventario();

		return $this->render('inventario/index.html.twig',
			['data' => $data]
		);
	}

	/**
	 * @Route("/add_taller/{id}", name="app_inventario_add_taller", requirements={"id"="\d+"})
	 */
	public function add_tallerAction($id = null, Request $request)
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
		$form = $this->createForm(AddTallerType::class, $producto);

		$form = $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$cantidad_taller = $form->getData()->getCantidadTaller();

			if ($cantidad_taller <= 0) {
				return $this->redirectToRoute('app_inventario_add_taller', [
					'id' => $id
				]);
			}
			if (($item->getCantidadInventario() - $cantidad_taller) >= 0 &&
				($item->getCantidadTaller() + $cantidad_taller) >= 0) {
				$item->setCantidadInventario($item->getCantidadInventario() - $cantidad_taller);
				$item->setCantidadTaller($item->getCantidadTaller() + $cantidad_taller);

				$detalles = $item->getMarca() . ',' . $item->getModelo() . ',' . $item->getPrecioC() . ',' . $cantidad_taller;
				$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'addtaller', $detalles);
				$em->persist($logs);

				$em->flush();
			} else {
				return $this->redirectToRoute('app_inventario_add_taller', [
					'id' => $id
				]);
			}

			return $this->redirectToRoute('app_inventario');
		}
		return $this->render("inventario/add_taller.html.twig", [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadInventario()
		]);
	}

	/**
	 * @Route("/darbaja/{id}", name="app_inventario_darbaja", requirements={"id"="\d+"})
	 */
	public function darbaja($id = null, Request $request)
	{
		if ($id !== null) {
			$item = $this->getDoctrine()->getRepository(Producto::class)->find($id);
		} else {
			return $this->redirectToRoute('app_inventario');
		}
		if (!$item) {
			return $this->redirectToRoute('app_inventario');
		}
		$form = $this->createForm(BajaType::class, $item);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$cant_bajas = $request->get('bajas');
			$notas = $request->get('notas');
			if ($notas == null) {
				$notas = "";
			}
			if ($cant_bajas != null && is_numeric($cant_bajas) && $cant_bajas > 0 && $cant_bajas <= $item->getCantidadInventario()) {
				$item->setCantidadInventario($item->getCantidadInventario() - $cant_bajas);
				$detalles = $item->getMarca() . ',' . $item->getModelo() . ',' . $item->getPrecioC() . ',' . $cant_bajas . ',' . $notas;
				$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'baja', $detalles);
				$em->persist($logs);
				$em->flush();
			} else {
				return $this->redirectToRoute('app_inventario_darbaja', [
					'id' => $id
				]);
			}
			return $this->redirectToRoute('app_inventario');
		}

		return $this->render('inventario/darbaja.html.twig', [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadInventario()
		]);
	}
}
