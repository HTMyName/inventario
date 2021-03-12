<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Facturas;
use App\Entity\FacturasProducto;
use App\Entity\FacturasServicio;
use App\Entity\Logs;
use App\Entity\Producto;
use App\Entity\Servicio;
use App\Entity\User;
use App\Form\FacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

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

		if ($form->isSubmitted() && $form->isValid()) {

			$em = $this->getDoctrine()->getManager();
			$createCliente = true;

			if ($factura->getIdCliente() === null) {
				$cliente = new Cliente();
				$clienteForm = $request->get('cliente_name');
				if ($clienteForm !== null) {
					$cliente->setName(ucfirst($clienteForm));
				} else {
					$createCliente = false;
				}
				$tellForm = $request->get('telefono_name');
				if ($tellForm !== null) {
					$cliente->setTell($tellForm);
				} else {
					$createCliente = false;
				}
				$cliente->setActive(1);
				$cliente->setDescuento(0);
				if ($createCliente === true) {
					$factura->setIdCliente($cliente);
					$em->persist($cliente);
				}
			} else {
				$cliente = $this->getDoctrine()->getRepository(Cliente::class)->find($factura->getIdCliente());
			}

			$factura->setFecha(new \DateTime("now"));
			$factura->setIdUser($this->getUser());
			$facturaTotalTemporal = 0;
			$array_facturas_p = [];
			$array_facturas_s = [];
			$detalles = "";

			$products_array = $request->get('productsArray');
			if ($products_array != null) {
				sort($products_array);
				$products_cant = array_count_values($products_array);
				$products_array = array_unique($products_array);

				foreach ($products_array as $product) {

					$factura_productos = new FacturasProducto();
					$product_data = $this->getDoctrine()->getRepository(Producto::class)->find($product);

					if ($product_data->getCantidadTaller() - $products_cant[$product] >= 0) {
						$factura_productos->setIdFactura($factura);
						$factura_productos->setIdProducto($product_data);
						$factura_productos->setPrecio($product_data->getPrecioV());
						$factura_productos->setCantidad($products_cant[$product]);
						$facturaTotalTemporal += ($product_data->getPrecioV() * $products_cant[$product]);
						$product_data->setCantidadTaller($product_data->getCantidadTaller() - $products_cant[$product]);
						$array_facturas_p[] = $factura_productos;
						$detalles = $product_data->getMarca() . ","
							. $product_data->getPrecioV() . ","
							. $factura_productos->getCantidad() . "|";
					}
				}
				foreach ($array_facturas_p as $prod) {
					$em->persist($prod);
					$factura->addProducto($prod);
				}
			}

			$service_array = $request->get('servicesArray');
			if ($service_array != null) {
				sort($service_array);
				$service_cant = array_count_values($service_array);
				$service_array = array_unique($service_array);

				foreach ($service_array as $service) {

					$factura_servicios = new FacturasServicio();
					$service_data = $this->getDoctrine()->getRepository(Servicio::class)->find($service);

					$factura_servicios->setIdFactura($factura);
					$factura_servicios->setIdServicio($service_data);
					$factura_servicios->setPrecio($service_data->getPrecio());
					$factura_servicios->setCantidad($service_cant[$service]);
					$facturaTotalTemporal += ($service_data->getPrecio() * $service_cant[$service]);
					$array_facturas_s[] = $factura_servicios;
					$detalles = $service_data->getName() . ","
						. $service_data->getPrecio() . ","
						. $factura_productos->getCantidad() . "|";
				}
				foreach ($array_facturas_s as $serv) {
					$em->persist($serv);
					$factura->addServicio($serv);
				}
			}

			$factura->setTotal($facturaTotalTemporal);
			$factura->setXpagar($facturaTotalTemporal - $facturaTotalTemporal * $cliente->getDescuento() / 100);

			$em->persist($factura);

			$log = $this->generateLogs($cliente, $factura, "factura", $detalles);
			$em->persist($log);

			if ($facturaTotalTemporal > 0 && $createCliente === true) {
				$em->flush();
				return $this->redirectToRoute('app_factura_detalles', [
					"id" => $factura->getId()
				]);
			}

			return $this->redirectToRoute('app_user_factura');
		}

		$items_data = $this->getDoctrine()->getRepository(Producto::class);
		$data = $items_data->showAllTaller();

		$service_data = $this->getDoctrine()->getRepository(Servicio::class);
		$datas = $service_data->findAllServices();

		return $this->render('taller/factura.html.twig',
			[
				'form' => $form->createView(),
				'data' => $data,
				'service' => $datas
			]
		);
	}

	/**
	 * @Route("/user_factura", name="app_user_factura")
	 */
	public function user_facturaAction()
	{
		$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getUserFacturas($this->getUser()->getId());

		return $this->render('taller/user_factura.html.twig', [
			"facturas" => $factura_repo
		]);
	}

	/**
	 * @Route("/user_factura/{id}", name="app_factura_detalles")
	 */
	public function detallesfacturaAction($id = null, Request $request)
	{
		if ($id !== null) {
			$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getFacturaById($id);
			$logsHistory = $this->getDoctrine()->getRepository(Logs::class)->getClientPays($id);
		} else {
			return $this->redirectToRoute('app_user_factura');
		}

		$factura = $this->getDoctrine()->getRepository(Facturas::class)->find($id);
		$form = $this->createForm(FacturaType::class, $factura);
		$form->handleRequest($request);

		$cliente = $this->getDoctrine()->getRepository(Cliente::class)->find($factura->getIdCliente());

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$cantidad = $request->get('pagar');
			$metodoPago = $request->get('tipopago');
			if ($metodoPago == 2) {
				$metodoPago = "Transfermovil";
			} else {
				$metodoPago = "Efectivo";
			}
			if ($cantidad != null && $cantidad <= $factura->getXpagar()) {
				$detalles = $metodoPago . "," . $cantidad;
				$log = $this->generateLogs($cliente, $factura, "pago", $detalles);
				$em->persist($log);
				$factura->setXpagar($factura->getXpagar() - $cantidad);
				$em->persist($factura);
				$em->flush();
				return $this->redirectToRoute('app_factura_detalles', ['id' => $id]);
			}

		}

		return $this->render('taller/detalles.html.twig', [
			"facturas" => $factura_repo,
			"logs" => $logsHistory,
			"form" => $form->createView()
		]);
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
			foreach ($user_repository as $user) {
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

	public function generateLogs($cliente, $factura, $tipo, $detalles): Logs
	{
		$log = new Logs();
		$log->setIdCliente($cliente);
		$log->setIdUser($this->getUser());
		$log->setIdFactura($factura);
		$log->setFecha($factura->getFecha());
		$log->setTipo($tipo);
		$log->setDetalles($detalles);
		return $log;
	}
}
