<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\AddItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemsController extends AbstractController
{
	/**
	 * @Route("/items", name="app_items")
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
		$data = $items_data->findAll();

		return $this->render('items/index.html.twig',
			['form' => $form->createView(), 'data' => $data]
		);
	}
}
