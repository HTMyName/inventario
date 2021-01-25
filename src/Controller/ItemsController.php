<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\AddItemType;
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
	 * @Route("/delete/{id}", name="app_items_delete")
	 */
	public function deleteAction($id = null)
	{
		if ($id !== null) {
			$this->getDoctrine()->getRepository(Producto::class)->deleteBy($id);
		}
		return $this->redirectToRoute('app_items');
	}

	/**
	 * @Route("/edit/{id}", name="app_items_edit")
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

}
