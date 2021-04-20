<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\AddClientType;
use App\Form\EditClientType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
			if (strlen($form->getData()->getTell()) == 8) {
				$cliente->setName(ucfirst($cliente->getName()));
				$cliente->setDescuento(0);
				$cliente->setActive(1);
				$em->persist($cliente);
				try {
					$em->flush();
				} catch (UniqueConstraintViolationException $e){
					//exeption
				}
			}
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
	 * @Route("/delete/{id}", name="app_client_delete", requirements={"id"="\d+"})
	 */
	public function deleteAction($id = null)
	{
		if ($id !== null) {
			$this->getDoctrine()->getRepository(Cliente::class)->deleteClient($id);
		}
		return $this->redirectToRoute('app_client');
	}

	/**
	 * @Route("/edit/{id}", name="app_client_edit", requirements={"id"="\d+"})
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
		$form = $this->createForm(EditClientType::class, $client);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->getData()->getDescuento() >= 0 &&
				$form->getData()->getDescuento() <= 100 &&
				strlen($form->getData()->getTell()) == 8) {

				$em->persist($client);
				$em->flush();
				return $this->redirectToRoute('app_client');
			}
			return $this->redirectToRoute('app_client_edit', [
				'id' => $id
			]);

		}
		return $this->render('client/edit.html.twig', ['form' => $form->createView()]);
	}
}
