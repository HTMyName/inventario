<?php

namespace App\Controller;

use App\Entity\Logs;
use App\Entity\System;
use App\Entity\User;
use App\Form\LogsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsController extends AbstractController
{
	/**
	 * @Route("/logs", name="app_logs")
	 */
	public function index(): Response
	{
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
			'users' => $users
		]);
	}

	/**
	 * @Route("/logs/all/{year}/{mes}/{tipo}/{user}", name="app_logs_api", methods={"GET"})
	 */
	public function getLogs($year = null, $mes = null, $tipo = "res", $user = "all")
	{
		if ($year == null) {
			$year = date('Y');
		}
		if ($mes == null) {
			$mes = date('n');
		}
		if ($tipo != "res") {
			$tipo = "det";
		}

		$logs = $this->getDoctrine()->getRepository(Logs::class)->getLogs($year, $mes, $tipo, $user);

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
