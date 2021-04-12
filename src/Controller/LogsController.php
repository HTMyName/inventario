<?php

namespace App\Controller;

use App\Entity\Logs;
use App\Entity\System;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsController extends AbstractController
{
	/**
	 * @Route("/logs", name="app_logs")
	 */
	public function index(): Response
	{
		$arrayTipos = [
			['additem', 'Productos Añadidos'],
			['addtaller', 'Inventario a Taller'],
			['addinventario', 'Taller a Inventario'],
			['baja', 'Bajas'],
			['factura', 'Facturas'],
			['pago', 'Pago Facturas'],
			['deudapaga', 'Deudas Pagas'],
			['salario', 'Salarios'],
			['prestamo', 'Prestamos'],
			['gasto', 'Gastos'],
		];

		$actual_year = date('Y');
		$year_start = $this->getDoctrine()->getRepository(System::class)->find(1)->getYearStart();
		$arrayYears = [];
		$arrayMes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
			'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

		for ($i = $year_start; $i <= $actual_year; $i++) {
			$arrayYears [] = [$i];
		}

		$users = $this->getDoctrine()->getRepository(User::class)->showUsersName();

		return $this->render('logs/index.html.twig', [
			'years' => $arrayYears,
			'actualYear' => $actual_year,
			'meses' => $arrayMes,
			'actualMes' => date('n'),
			'users' => $users,
			'tipos' => $arrayTipos
		]);
	}

	/**
	 * @Route("/logs/{year}/{mes}/{tipo}/{user}", name="app_logs_api", methods={"POST"})
	 */
	public function getLogs($year = null, $mes = null, $tipo = "all", $user = "all"): JsonResponse
	{
		if ($year == null) {
			$year = date('Y');
		}
		if ($mes == null) {
			$mes = date('n');
		}

		$logs = $this->getDoctrine()->getRepository(Logs::class)->getLogs($year, $mes, $tipo, $user);
		$arrayLogs = [];
		$cliente = $detalles = '';
		foreach ($logs as $log) {
			if ($log->getIdCliente() != null) {
				$cliente = $log->getIdCliente()->getName();
			}else{
				$cliente = null;
			}
			if ($log->getTipo() == 'factura') {
				$detalles = $log->getIdFactura()->getId();
			}else{
				$detalles = $log->getDetalles();
			}
			$arrayLogs [] = [
				'fecha' => date_format($log->getFecha(), 'd/m/Y'),
				'detalles' => $detalles,
				'user' => $log->getIdUser()->getName(),
				'cliente' => $cliente,
				'tipo' => $log->getTipo()
			];
		}

		return new JsonResponse($arrayLogs, Response::HTTP_OK);

	}

	public function generateLogs($cliente, $factura, $user, $tipo, $detalles): Logs
	{
		$log = new Logs();
		$log->setIdCliente($cliente);
		$log->setIdUser($user);
		$log->setIdFactura($factura);
		if ($factura != null) {
			$log->setFecha($factura->getFecha());
		} else {
			$log->setFecha(new \DateTime('now'));
		}
		$log->setTipo($tipo);
		$log->setDetalles($detalles);
		return $log;
	}
}
