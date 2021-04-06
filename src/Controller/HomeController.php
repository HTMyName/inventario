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

	/**
	 * @Route("/home/sales", name="home_sales", methods={"GET"})
	 */
	public function getChart()
	{
		$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getAllUserFacturas($this->getUser());

		$array_prod = [
			0 => 0,        //ene
			1 => 0,        //feb
			2 => 0,        //mar
			3 => 0,        //abr
			4 => 0,        //may
			5 => 0,        //jun
			6 => 0,        //jul
			7 => 0,        //ago
			8 => 0,        //sep
			9 => 0,        //oct
			10 => 0,       //nov
			11 => 0        //dic
		];

		$array_serv = [
			0 => 0,        //ene
			1 => 0,        //feb
			2 => 0,        //mar
			3 => 0,        //abr
			4 => 0,        //may
			5 => 0,        //jun
			6 => 0,        //jul
			7 => 0,        //ago
			8 => 0,        //sep
			9 => 0,        //oct
			10 => 0,       //nov
			11 => 0        //dic
		];

		foreach ($facturas as $factura) {

			$fecha = $factura->getFecha();
			$fecha_formated = $fecha->format('Y-m-d H:i:s');
			$fecha_formated = strtotime($fecha_formated);
			$mes = date('n', $fecha_formated);
			$mes -= 1;

			foreach ($factura->getProductos() as $producto) {
				$cantTmp = (int)$array_prod[$mes] + (int)$producto->getCantidad();
				$array_prod[$mes] = [$cantTmp];
			}

			foreach ($factura->getServicios() as $servicio) {
				$cantTmp = (int)$array_serv[$mes] + (int)$servicio->getCantidad();
				$array_serv[$mes] = [$cantTmp];
			}
		}

		$json = [
			'productos' => $array_prod,
			'servicios' => $array_serv
		];

		return new JsonResponse($json, Response::HTTP_OK);
		//return $this->render('test.html.twig');
	}
}