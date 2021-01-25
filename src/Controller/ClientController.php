<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\AddClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
	/**
	 * @Route("", name="app_client")
	 */
	public function index(Request $request)
	{
		$cliente = new Cliente();
		$form = $this->createForm(AddClientType::class, $cliente);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$cliente->setActive(1);
			$em->persist($cliente);
			$em->flush();
			return $this->redirectToRoute('app_client');
		}
		$client_data = $this->getDoctrine()->getRepository(Cliente::class);
		$data = $client_data->findAllClients();
		return $this->render('client/index.html.twig',
			[
				'form' => $form->createView(),
				'data' => $data
			]
		);
	}

	/**
	 * @Route("/delete/{id}", name="app_client_delete")
	 */
	public function deleteAction($id = null)
	{
		if ($id !== null) {
			$this->getDoctrine()->getRepository(Cliente::class)->deleteClient($id);
		}
		return $this->redirectToRoute('app_client');
	}

	/**
	 * @Route("/edit/{id}", name="app_client_edit")
	 */
	public function editAction($id = null, Request $request)
	{
		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$client = $em->getRepository(Cliente::class)->find($id);
		} else {
			return $this->redirectToRoute('app_client');
		}
		if (!$client) {
			return $this->redirectToRoute('app_client');
		}
		$form = $this->createForm(AddClientType::class, $client);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($client);
			$em->flush();
			return $this->redirectToRoute('app_client');
		}
		return $this->render('client/edit.html.twig', ['form' => $form->createView()]);
	}
}
