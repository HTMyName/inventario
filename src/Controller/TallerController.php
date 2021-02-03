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
	 * @Route("/get_producto/{id}", name="get_producto", methods={"POST"}, requirements={"id"="\d+"})
	 */
	public function get_productoAction($id = null)
	{
		if ($id !== null) {
			$producto = $this->getDoctrine()->getRepository(Producto::class)->find($id);
		}

		$response = new JsonResponse();

		$response->setData(['marca' => $producto->getMarca(), 'modelo' => $producto->getModelo(), 'precio' => $producto->getPrecioV()]);

		return $response;
	}

	/**
	 * @Route("/factura", name="app_factura")
	 */
	public function facturaAction(Request $request)
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
	 * @Route("/get_user/{data}", name="app_taller_get_user")
	 */
	public function get_userAction($data = null)
	{
		if ($data !== null) {
			$user_repository = $this->getDoctrine()->getRepository(Cliente::class)->getClientName($data);
		}

	}
}
