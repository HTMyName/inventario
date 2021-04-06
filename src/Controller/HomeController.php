<?php

namespace App\Controller;

use App\Entity\Facturas;
use App\Entity\System;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route("/home", name="app_home")
	 */
	public function index(): Response
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);

		$salario = $this->getDoctrine()->getRepository(User::class)->sumAllSalariosBy($this->getUser());

		$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getUserFacturas($this->getUser());
		$total_productos = $total_servicios = 0;

		foreach ($facturas as $factura) {
			foreach ($factura->getProductos() as $producto) {
				$total_productos += $producto->getCantidad();
			}
			foreach ($factura->getServicios() as $servicio) {
				$total_servicios += $servicio->getCantidad();
			}
		}

		return $this->render('home/index.html.twig', [
			'caja' => $system->getCaja(),
			'salario' => $salario,
			'total_productos' => $total_productos,
			'total_servicios' => $total_servicios
		]);
	}

	/**
	 * @Route("/home/vaciarcaja", name="app_vaciarcaja", methods={"POST"})
	 */
	public function vaciarcaja()
	{
		if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
			$system = $this->getDoctrine()->getRepository(System::class)->find(1);
			$system->setCaja(0);
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			return new JsonResponse('ok', Response::HTTP_OK);
		}
		return new JsonResponse('forbidden', Response::HTTP_FORBIDDEN);
	}
}