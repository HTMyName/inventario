<?php

namespace App\Controller;

use App\Entity\Servicio;
use App\Form\ServicioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/services")
 */
class ServicesController extends AbstractController
{
	/**
	 * @Route("", name="app_services")
	 */
	public function index(Request $request)
	{
		$servicio = new Servicio();
		$form = $this->createForm(ServicioType::class, $servicio);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$servicio->setActive(1);
			$em->persist($servicio);
			$em->flush();
			return $this->redirectToRoute('app_services');
		}

		$servicio_data = $this->getDoctrine()->getRepository(Servicio::class);
		$data = $servicio_data->findAllClients();

		return $this->render('services/index.html.twig', ['form' => $form->createView(), 'data' => $data]);
	}

	/**
	 * @Route("/delete/{id}", name="app_services_delete", requirements={"id"="\d+"})
	 */
	public function deleteAction($id = null)
	{
		if ($id !== null) {
			$this->getDoctrine()->getRepository(Servicio::class)->deleteBy($id);
		}

		return $this->redirectToRoute('app_services');
	}

	/**
	 * @Route("/edit/{id}", name="app_services_edit", requirements={"id"="\d+"})
	 */
	public function editAction($id = null, Request $request)
	{
		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$servicio = $em->getRepository(Servicio::class)->find($id);
		} else {
			return $this->redirectToRoute('app_services');
		}

		if (!$servicio) {
			return $this->redirectToRoute('app_services');
		}

		$form = $this->createForm(ServicioType::class, $servicio);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($servicio);
			$em->flush();
			return $this->redirectToRoute('app_services');
		}
		return $this->render('services/edit.html.twig', ['form' => $form->createView()]);
	}
}
