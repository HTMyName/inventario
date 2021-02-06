<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Facturas;
use App\Entity\Producto;
use App\Entity\Servicio;
use App\Form\FacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/taller")
 */
class TallerController extends AbstractController
{
	/**
	 * @Route("", name="app_taller")
	 */
	public function index(): Response
	{
		$items_data = $this->getDoctrine()->getRepository(Producto::class);
		$data = $items_data->showAllTaller();

		return $this->render('taller/index.html.twig',
			[
				'data' => $data
			]
		);
	}

	/**
	 * @Route("/factura", name="app_factura")
	 */
	public function facturaAction(Request $request): Response
	{
		$factura = new Facturas();
		$form = $this->createForm(FacturaType::class, $factura);
		$form->handleRequest($request);

		$service_array = $request->get('service');

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$factura->getId();

			//consulta añadiendo valores a la tabla facturas_productos

			$factura->setFecha(new \DateTime("now"));
			$factura->setIdUser($this->getUser()->getId());
			$factura->setTotal(10);

			$service_data = $this->getDoctrine()->getRepository(Servicio::class)->find(1);
			$factura->addServicio($service_data);

			$em->persist($factura);
			$em->flush();
			dump($factura);
			//return $this->redirectToRoute('app_taller');
		}

		$items_data = $this->getDoctrine()->getRepository(Producto::class);
		$data = $items_data->showAllTaller();

		return $this->render('taller/factura.html.twig',
			[
				'form' => $form->createView(),
				'data' => $data
			]
		);
	}

	/**
	 * @Route("/get_user/{data}", name="app_taller_get_user", methods={"POST"})
	 */
	public function get_userAction($data = null): Response
	{
		$user_repository = "";

		if ($data !== null) {
			$user_repository = $this->getDoctrine()->getRepository(Cliente::class)->getClientName($data);
		}

		$response = "";

		if ($user_repository) {
			foreach ($user_repository as $user){
				$response .= "<option value='{$user['id']}' id='{$user['tell']}'>{$user['name']}</option>";
			}
		}

		return new Response($response);

	}

	/**
	 * @Route("/get_producto/{data}", name="app_taller_get_producto", methods={"POST"})
	 */
	public function get_productoAction($data = null): JsonResponse
	{
		$producto_repository = "";

		if ($data !== null) {
			$producto_repository = $this->getDoctrine()->getRepository(Producto::class)->showTallerBy($data);
		}

		$json = new JsonResponse();

		if (!$producto_repository) {
			$producto_repository = [];
		}

		$json->setData($producto_repository);

		return $json;
	}

	/**
	 * @Route("/get_servicio/{data}", name="app_taller_get_servicio", methods={"POST"})
	 */
	public function get_servicioAction($data = null): JsonResponse
	{
		$servicio_repository = "";

		if ($data !== null) {
			$servicio_repository = $this->getDoctrine()->getRepository(Servicio::class)->showServiceBy($data);
		}

		$json = new JsonResponse();

		if (!$servicio_repository) {
			$servicio_repository = [];
		}

		$json->setData($servicio_repository);

		return $json;
	}
}
