<?php

namespace App\Controller;

use App\Entity\Facturas;
use App\Entity\Images;
use App\Entity\Logs;
use App\Entity\System;
use App\Entity\User;
use App\Form\FondoType;
use App\Form\GastosType;
use App\Form\ImageType;
use App\Form\SystemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/system")
 */
class SystemController extends AbstractController
{
	private $logsOb;

	public function __construct()
	{
		$this->logsOb = new LogsController();
	}

	/**
	 * @Route("", name="app_system")
	 */
	public function system()
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$salarios = $this->getDoctrine()->getRepository(User::class)->sumAllSalarios();
		$xcobrar = $this->getDoctrine()->getRepository(Facturas::class)->getSumXpagar();
		$xcobrar2 = number_format($xcobrar[0]["xpagar"], 2, '.', '');
		$bajas = $this->getDoctrine()->getRepository(Logs::class)->getBajas();

		$total_bajas = 0;
		if ($bajas) {
			foreach ($bajas as $detalles) {
				$explode = explode(",", $detalles["detalles"], 5);
				$total_bajas += $explode[2] * $explode[3];
			}
		}

		return $this->render('system/system.html.twig', [
			"system" => $system,
			"salario" => $salarios,
			"xcobrar" => $xcobrar2,
			"bajas" => $total_bajas
		]);
	}

	/**
	 * @Route("/configuration", name="app_system_configuration")
	 */
	public function index(Request $request): Response
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$form = $this->createForm(SystemType::class, $system);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($system);
			$em->flush();
		}

		return $this->render('system/index.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/fondo", name="app_system_fondo")
	 */
	public function fondo(Request $request)
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$form = $this->createForm(FondoType::class, $system);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$idform = $form->getData()->getId();
			if ($idform == 1) {
				if (is_numeric($request->get("cantidad-pagar"))) {
					$cantidad_pagar = $request->get("cantidad-pagar");
				} else {
					return $this->redirectToRoute('app_system_fondo');
				}
			} else {
				return $this->redirectToRoute('app_system_fondo');
			}
			if ($system->getRecuperado() + $cantidad_pagar >= 0) {
				$system->setRecuperado($system->getRecuperado() + $cantidad_pagar);
				$em->persist($system);
				$em->flush();
			}
		}
		return $this->render('system/fondo.html.twig', [
			'form' => $form->createView(),
			'system' => $system
		]);
	}

	/**
	 * @Route("/fondo_ganacia", name="app_system_fondo_ganacia")
	 */
	public function fondo_ganacia(Request $request)
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$form = $this->createForm(FondoType::class, $system);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$idform = $form->getData()->getId();
			if ($idform == 1) {
				if (is_numeric($request->get("cantidad-pagar")) && $request->get("cantidad-pagar") >= 0) {
					$cantidad_pagar = $request->get("cantidad-pagar");
				} else {
					return $this->redirectToRoute('app_system_fondo_ganacia');
				}
			} else {
				return $this->redirectToRoute('app_system_fondo_ganacia');
			}
			if ($system->getRecuperado() - $cantidad_pagar >= 0) {
				$system->setRecuperado($system->getRecuperado() - $cantidad_pagar);
				$system->setGanancia($system->getGanancia() + $cantidad_pagar);
				$em->persist($system);
				$em->flush();
			}
		}
		return $this->render('system/fondo_ganacia.html.twig', [
			'form' => $form->createView(),
			'system' => $system
		]);
	}

	/**
	 * @Route("/ganancia_fondo", name="app_system_ganancia_fondo")
	 */
	public function ganancia_fondo(Request $request)
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$form = $this->createForm(FondoType::class, $system);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$idform = $form->getData()->getId();
			if ($idform == 1) {
				if (is_numeric($request->get("cantidad-pagar")) && $request->get("cantidad-pagar") >= 0) {
					$cantidad_pagar = $request->get("cantidad-pagar");
				} else {
					return $this->redirectToRoute('app_system_ganancia_fondo');
				}
			} else {
				return $this->redirectToRoute('app_system_ganancia_fondo');
			}
			if ($system->getGanancia() - $cantidad_pagar >= 0) {
				$system->setGanancia($system->getGanancia() - $cantidad_pagar);
				$system->setRecuperado($system->getRecuperado() + $cantidad_pagar);
				$em->persist($system);
				$em->flush();
			}
		}
		return $this->render('system/ganancia_fondo.html.twig', [
			'form' => $form->createView(),
			'system' => $system
		]);
	}

	/**
	 * @Route("/prestamo", name="app_system_prestamo")
	 */
	public function prestamo(Request $request)
	{
		$userid = $cantidad = 0;
		$usuarios = $this->getDoctrine()->getRepository(User::class)->showUsersName();
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);

		if ($request->get('userlist') != null && is_numeric($request->get('userlist')) && $request->get('userlist') > 0) {
			$userid = $request->get('userlist');
		}
		if ($request->get('cantidad') != null && is_numeric($request->get('cantidad')) && $request->get('cantidad') > 0) {
			$cantidad = $request->get('cantidad');
		}

		if ($userid > 0 && $cantidad > 0 && ($system->getGanancia() - $cantidad >= 0)) {
			$em = $this->getDoctrine()->getManager();
			$user = $this->getDoctrine()->getRepository(User::class)->find($userid);
			if ($user) {
				$system->setGanancia($system->getGanancia() - $cantidad);
				$user->setPayTotal($user->getPayTotal() - $cantidad);
				$logs = $this->logsOb->generateLogs(null, null, $user, 'prestamo', $cantidad);
				$em->persist($logs);
				$em->persist($system);
				$em->persist($user);
				$em->flush();
				return $this->redirectToRoute('app_system_prestamo');
			}
		}

		return $this->render('system/prestamo.html.twig', [
			'users' => $usuarios,
			'ganancia' => $system->getGanancia()
		]);
	}

	/**
	 * @Route("/gastos", name="app_system_gastos")
	 */
	public function gastos(Request $request)
	{
		$system = $this->getDoctrine()->getRepository(System::class)->find(1);
		$form = $this->createForm(GastosType::class, $system);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$gastos = $request->get('gastos_input');
			$nota = $request->get('nota_input');
			if (($gastos != "" && is_numeric($gastos) && $gastos > 0 && $gastos <= $system->getGanancia())) {
				$em = $this->getDoctrine()->getManager();

				$system->setGastos($system->getGastos() + $gastos);
				$system->setGanancia($system->getGanancia() - $gastos);

				$detalles = $gastos . ',' . $nota;

				$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'gasto', $detalles);
				$em->persist($logs);
				$em->persist($system);
				$em->flush();
			} else {
				$this->redirectToRoute('app_system_gastos');
			}
		}

		return $this->render('system/gastos.html.twig', [
			'form' => $form->createView(),
			'system' => $system
		]);
	}

	public function systemSettings()
	{
		$settings = $this->getDoctrine()->getRepository(System::class)->find(1);
		return $settings;
	}

	public function getDateCopyright()
	{
		$date = date('Y');
		if ($date == 2021) {
			$date = "";
		} else {
			$date = "- $date";
		}
		return $date;
	}
}
