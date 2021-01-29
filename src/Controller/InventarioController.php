<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\AddTallerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inventario")
 */
class InventarioController extends AbstractController
{
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

			if (($item->getCantidadInventario() - $form->getData()->getCantidadTaller()) >= 0 &&
				($item->getCantidadTaller() + $form->getData()->getCantidadTaller()) >= 0) {

				$item->setCantidadInventario($item->getCantidadInventario() - $form->getData()->getCantidadTaller());
				$item->setCantidadTaller($item->getCantidadTaller() + $form->getData()->getCantidadTaller());
				$em->flush();

			} else {
				//mensajito de error
			}

			return $this->redirectToRoute('app_inventario');
		}
		return $this->render("inventario/add_taller.html.twig", [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadInventario()
		]);
	}
}
