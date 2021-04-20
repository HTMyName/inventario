<?php

namespace App\Controller;

use App\Entity\Facturas;
use App\Entity\System;
use App\Entity\ToDo;
use App\Entity\User;
use App\Form\ToDoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home")
 */
class HomeController extends AbstractController
{
	/**
	 * @Route("", name="app_home")
	 */
	public function index(): Response
	{
		$caja = $this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getCaja();

		$system = $this->getDoctrine()->getRepository(System::class)->find(1);

		$salario = $this->getDoctrine()->getRepository(User::class)->sumAllSalariosBy($this->getUser());

		if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
			$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getMesFacturas(null);
			$todo = $this->getDoctrine()->getRepository(ToDo::class)->findAllTodo(null);
		} else {
			$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getMesFacturas($this->getUser());
			$todo = $this->getDoctrine()->getRepository(ToDo::class)->findAllTodo($this->getUser());
		}

		$total_todo = ceil(count($todo) / 5);

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
			'caja' => $caja,
			'salario' => $salario,
			'total_productos' => $total_productos,
			'total_servicios' => $total_servicios,
			'todo' => $todo,
			'total_todo' => $total_todo
		]);
	}

	/**
	 * @Route("/todo", name="app_home_todo")
	 */
	public function todo(Request $request)
	{
		$todo = new ToDo();
		$form = $this->createForm(ToDoType::class, $todo);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$todo->setFecha(new \DateTime('now'));
			$todo->setCompleted(0);
			if ($todo->getIdUser() == null) {
				$todo->setVisible(1);
			} else {
				$todo->setVisible(0);
			}
			$em->persist($todo);
			$em->flush();
			return $this->redirectToRoute('app_home');
		}
		return $this->render('home/todo.html.twig', [
			'form' => $form->createView(),
			'task_name' => 'Nueva Tarea'
		]);
	}

	/**
	 * @Route("/todo/{id}", name="app_home_todo_edit", requirements={"id"="\d+"})
	 */
	public function editTodo($id = null, Request $request)
	{
		if ($id != null) {
			$todo = $this->getDoctrine()->getRepository(ToDo::class)->find($id);
		} else {
			return $this->redirectToRoute('app_home_todo_edit', [
				'id' => $id
			]);
		}
		$form = $this->createForm(ToDoType::class, $todo);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($todo);
			$em->flush();
			return $this->redirectToRoute('app_home');
		}
		return $this->render('home/todo.html.twig', [
			'form' => $form->createView(),
			'task_name' => 'Actualizar Tarea'
		]);
	}

	/**
	 * @Route("/todo/completar/{value}/{id}", name="app_todo_completar", methods={"GET"}, requirements={"id"="\d+","value"="\d+"})
	 */
	public function todocompletar($value = 1, $id = null): JsonResponse
	{
		if ($id != null) {
			$todo = $this->getDoctrine()->getRepository(ToDo::class)->find($id);
			if ($todo->getIdUser() == null || $todo->getIdUser() == $this->getUser() || $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
				$em = $this->getDoctrine()->getManager();
				$todo->setCompleted($value);
				$em->persist($todo);
				$em->flush();
				return new JsonResponse('ok', JsonResponse::HTTP_OK);
			}
		}
		return new JsonResponse('forbidden', JsonResponse::HTTP_FORBIDDEN);
	}

	/**
	 * @Route("/todo/eliminar/{id}", name="app_todo_eliminar", requirements={"id"="\d+"})
	 */
	public function todoEliminar($id = null)
	{
		if ($id != null) {
			$todo = $this->getDoctrine()->getRepository(ToDo::class)->find($id);
			if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
				$em = $this->getDoctrine()->getManager();
				$em->remove($todo);
				$em->flush();
			}
		}
		return $this->redirectToRoute('app_home');
	}

	/**
	 * @Route("/vaciarcaja/{id}", name="app_vaciarcaja", methods={"POST"}, requirements={"id"="\d+"})
	 */
	public function vaciarcaja($id = null)
	{
		if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN" && $id != null) {
			$user = $this->getDoctrine()->getRepository(User::class)->find($id);
			$user->setCaja(0);
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			return new JsonResponse('ok', Response::HTTP_OK);
		}
		return new JsonResponse('forbidden', Response::HTTP_FORBIDDEN);
	}

	/**
	 * @Route("/sales", name="home_sales", methods={"GET"})
	 */
	public function getChart(): JsonResponse
	{

		if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
			$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getYearFacturas(null);
		} else {
			$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getYearFacturas($this->getUser());
		}

		//$facturas = $this->getDoctrine()->getRepository(Facturas::class)->getAllUserFacturas($this->getUser());

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
				$array_prod[$mes] = $array_prod[$mes] + $producto->getCantidad();
			}

			foreach ($factura->getServicios() as $servicio) {
				$array_serv[$mes] = $array_serv[$mes] + $servicio->getCantidad();
			}
		}

		$json = [
			'productos' => $array_prod,
			'servicios' => $array_serv
		];

		return new JsonResponse($json, Response::HTTP_OK);
	}
}