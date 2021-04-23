<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Facturas;
use App\Entity\FacturasProducto;
use App\Entity\FacturasServicio;
use App\Entity\Logs;
use App\Entity\Producto;
use App\Entity\Servicio;
use App\Entity\System;
use App\Entity\User;
use App\Form\AddInventarioType;
use App\Form\FacturaType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Mpdf\Mpdf;
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
	private $logsOb;

	public function __construct()
	{
		$this->logsOb = new LogsController();
	}

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

			$user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
			$usercant2 = $this->getDoctrine()->getRepository(User::class)->countActiveUsers();
			$usercant = count($usercant2);
			$system = $this->getDoctrine()->getRepository(System::class)->find(1);

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
					if (strlen($tellForm) == 8) {
						$cliente->setTell($tellForm);
					} else {
						$createCliente = false;
					}
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

			$factura->setIdUser($user);
			$factura->setFecha(new \DateTime("now"));
			$factura->setSaldoRetenidoP(0);
			$factura->setSaldoRetenidoS(0);
			$factura->setSaldoRetenidoI(0);
			$factura->setSaldoRetenidoFI(0);
			$factura->setSaldoRetenidoFG(0);
			$factura->setActive(1);
			$facturaTotalTemporal = 0;
			$facturaTotalRealTeamporal = 0;
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
						//tabla facturas producto
						$factura_productos->setIdFactura($factura);
						$factura_productos->setIdProducto($product_data);
						$factura_productos->setPrecio($product_data->getPrecioV());
						$factura_productos->setCantidad($products_cant[$product]);
						$product_data->setCantidadTaller($product_data->getCantidadTaller() - $products_cant[$product]);

						//array y logs
						$array_facturas_p[] = $factura_productos;
						$detalles = $product_data->getMarca() . ","
							. $product_data->getPrecioV() . ","
							. $factura_productos->getCantidad() . "|";

						//tabla facturas
						$ganancia_producto = ($product_data->getPrecioV() - $product_data->getPrecioC()) * $products_cant[$product];
						$ganancia_real_producto = $ganancia_producto - ($ganancia_producto * $cliente->getDescuento() / 100);

						$total_venta_producto = $product_data->getPrecioV() * $products_cant[$product];
						$total_real_venta_producto = $total_venta_producto - ($ganancia_producto * $cliente->getDescuento() / 100);

						$facturaTotalTemporal += $total_venta_producto;
						$facturaTotalRealTeamporal += $total_real_venta_producto;

						//$total_real_venta_producto = $product_data->getPrecioC() - $product_data->getPrecioV() * $cliente->getDescuento() / 100;
						//$ganancia_producto = ($total_real_venta_producto - $product_data->getPrecioC()) * $products_cant[$product];
						$saldo_r_producto_t = $ganancia_real_producto * $product_data->getXcientoganancia() / 100;
						$saldo_r_indirecto_t = $ganancia_real_producto * $system->getWinproduct() / 100 * ($usercant - 1);
						$inversion_recuperada = $ganancia_real_producto - $saldo_r_producto_t - $saldo_r_indirecto_t;

						$factura->setSaldoRetenidoP($factura->getSaldoRetenidoP() + $saldo_r_producto_t);
						$factura->setSaldoRetenidoI($factura->getSaldoRetenidoI() + $saldo_r_indirecto_t);
						$factura->setSaldoRetenidoFI($factura->getSaldoRetenidoFI() + ($product_data->getPrecioC() * $products_cant[$product]));
						$factura->setSaldoRetenidoFG($factura->getSaldoRetenidoFG() + $inversion_recuperada);
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
					//tabla factura servicio
					$factura_servicios = new FacturasServicio();
					$service_data = $this->getDoctrine()->getRepository(Servicio::class)->find($service);
					$factura_servicios->setIdFactura($factura);
					$factura_servicios->setIdServicio($service_data);
					$factura_servicios->setPrecio($service_data->getPrecio());
					$factura_servicios->setCantidad($service_cant[$service]);

					//array y logs
					$array_facturas_s[] = $factura_servicios;
					$detalles = $service_data->getName() . ","
						. $service_data->getPrecio() . ","
						. $factura_servicios->getCantidad() . "|";

					//tabla facturas
					$total_venta_servicio = $service_data->getPrecio() * $service_cant[$service];
					$total_real_venta_servicio = $total_venta_servicio - $total_venta_servicio * $cliente->getDescuento() / 100;

					$facturaTotalTemporal += $total_venta_servicio;
					$facturaTotalRealTeamporal += $total_real_venta_servicio;

					//$total_real_venta_servicio = $service_data->getPrecio() - $service_data->getPrecio() * $cliente->getDescuento() / 100;
					$saldo_r_servicio_t = $total_real_venta_servicio * $service_data->getXcientoganancia() / 100;
					$saldo_r_indirecto_t = $total_real_venta_servicio * $system->getWinservice() / 100 * ($usercant - 1);
					$inversion_recuperada = $total_real_venta_servicio - $saldo_r_servicio_t - $saldo_r_indirecto_t;

					$factura->setSaldoRetenidoS($factura->getSaldoRetenidoS() + $saldo_r_servicio_t);
					$factura->setSaldoRetenidoI($factura->getSaldoRetenidoI() + $saldo_r_indirecto_t);
					//$factura->setSaldoRetenidoFI($factura->getSaldoRetenidoFI() + $service_data->getPrecio() - $saldo_r_servicio_t - $saldo_r_indirecto_t);
					$factura->setSaldoRetenidoFG($factura->getSaldoRetenidoFG() + $inversion_recuperada);
				}
				foreach ($array_facturas_s as $serv) {
					$em->persist($serv);
					$factura->addServicio($serv);
				}
			}

			if ($service_array == null && $products_array == null) {
				return $this->redirectToRoute('app_factura');
			}

			$factura->setTotal($facturaTotalTemporal);
			$factura->setTotalReal($facturaTotalRealTeamporal);
			$factura->setXpagar($factura->getTotalReal());
			$factura->setDescuento($cliente->getDescuento());

			$em->persist($factura);

			$log = $this->logsOb->generateLogs($cliente, $factura, $this->getUser(), "factura", $detalles);
			$em->persist($log);

			if ($facturaTotalTemporal > 0 && $createCliente === true) {
				try {
					$em->flush();
				} catch (UniqueConstraintViolationException $e) {
					return $this->redirectToRoute('app_factura');
				}
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
	 * @Route("/eliminar_factura/{id}", name="app_eliminar_factura", requirements={"id"="\d+"} ,methods={"POST"})
	 */
	public function eliminar($id = null)
	{
		if ($id != null) {
			$factura = $this->getDoctrine()->getRepository(Facturas::class)->find($id);
			if ($factura) {
				$productos = $this->getDoctrine()->getRepository(FacturasProducto::class)->findBy([
					'id_factura' => $id
				]);
				$servicios = $this->getDoctrine()->getRepository(FacturasServicio::class)->findBy([
					'id_factura' => $id
				]);

				if ($factura->getTotalReal() == $factura->getXpagar()) {
					if ($this->getUser() == $factura->getIdUser() || $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
						$em = $this->getDoctrine()->getManager();
						foreach ($productos as $producto) {
							$producto->getIdProducto()->setCantidadTaller($producto->getIdProducto()->getCantidadTaller() + $producto->getCantidad());
							$em->remove($producto);
						}
						foreach ($servicios as $servicio) {
							$em->remove($servicio);
						}
						$factura->setActive(0);
						$this->addFlash('success', 'Factura eliminada');
						$em->persist($factura);
						$em->flush();
					}
				}
			}
		}
		return new Response("ok");
	}

	/**
	 * @Route("/user_factura", name="app_user_factura")
	 */
	public function user_facturaAction()
	{
		$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getUserFacturas($this->getUser());

		return $this->render('taller/user_factura.html.twig', [
			"facturas" => $factura_repo,
			'title' => 'Mis Facturas'
		]);
	}

	/**
	 * @Route("/user_factura_xcobrar", name="app_user_factura_xcobrar")
	 */
	public function facturaXcobrar()
	{
		$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getFacturasXcobrar();

		return $this->render('taller/user_factura.html.twig', [
			"facturas" => $factura_repo,
			'title' => 'Por Cobrar'
		]);
	}

	/**
	 * @Route("/user_factura/{id}", name="app_factura_detalles", requirements={"id"="\d+"})
	 */
	public function detallesfacturaAction($id = null, Request $request)
	{

		if ($id !== null) {
			$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getFacturaById($id);
			$logsHistory = $this->getDoctrine()->getRepository(Logs::class)->getClientPays($id);
			$factura = $this->getDoctrine()->getRepository(Facturas::class)->find($id);
		} else {
			return $this->redirectToRoute('app_user_factura');
		}

		if ($factura->getActive() == 0) {
			return $this->redirectToRoute('app_user_factura');
		}

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
			if ($cantidad != null && $cantidad > 0 && $cantidad <= $factura->getXpagar()) {
				$system = $this->getDoctrine()->getRepository(System::class)->find(1);
				$userforcaja = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
				$userforcaja->setCaja($userforcaja->getCaja() + $cantidad);

				$detalles = $metodoPago . "," . $cantidad;
				$log = $this->logsOb->generateLogs($cliente, $factura, $this->getUser(), "pago", $detalles);
				$em->persist($log);
				$factura->setXpagar($factura->getXpagar() - $cantidad);

				if ($factura->getXpagar() == 0) {
					$user = $this->getDoctrine()->getRepository(User::class)->find($factura->getIdUser());
					$user->setPayV($user->getPayV() + $factura->getSaldoRetenidoP());
					$user->setPayS($user->getPayS() + $factura->getSaldoRetenidoS());

					$users_indirecto = $this->getDoctrine()->getRepository(User::class)->getAllUsersExept($factura->getIdUser());
					$users_cant = count($users_indirecto);
					foreach ($users_indirecto as $userid) {
						$usertmp = $this->getDoctrine()->getRepository(User::class)->find($userid["id"]);
						$usertmp->setPayV($usertmp->getPayV() + $factura->getSaldoRetenidoI() / $users_cant);
						$em->persist($usertmp);
					}

					$logsPagos = $this->getDoctrine()->getRepository(Logs::class)->findBy([
						'id_factura' => $id,
						'tipo' => 'pago'
					]);

					$total_para_banco = 0;

					foreach ($logsPagos as $logdetalles) {
						$tipo = explode(',', $logdetalles->getDetalles(), 2);
						if ($tipo[0] == "Transfermovil") {
							$total_para_banco += $tipo[1];
						}
					}

					if ($metodoPago == "Transfermovil") {
						$total_para_banco += $cantidad;
					}

					$system->setInversion($system->getInversion() - $factura->getSaldoRetenidoFI());
					$system->setRecuperado($system->getRecuperado() + $factura->getSaldoRetenidoFI());
					$system->setGanancia($system->getGanancia() + $factura->getSaldoRetenidoFG());
					$system->setBanco($system->getBanco() + $total_para_banco);

				}
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
	 * @Route("/add_inventario/{id}", name="app_taller_add_inventario", requirements={"id"="\d+"})
	 */
	public function add_tallerAction($id = null, Request $request)
	{
		$producto = new Producto();
		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$item = $em->getRepository(Producto::class)->find($id);
		} else {
			return $this->redirectToRoute('app_taller');
		}
		if (!$item) {
			return $this->redirectToRoute('app_taller');
		}

		//creando formulario
		$form = $this->createForm(AddInventarioType::class, $producto);

		$form = $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$cantidad_inventario = $form->getData()->getCantidadInventario();

			if ($cantidad_inventario <= 0) {
				return $this->redirectToRoute('app_taller_add_inventario', [
					'id' => $id
				]);
			}
			if (($item->getCantidadTaller() - $cantidad_inventario) >= 0 &&
				($item->getCantidadInventario() + $cantidad_inventario) >= 0) {
				$item->setCantidadTaller($item->getCantidadTaller() - $cantidad_inventario);
				$item->setCantidadInventario($item->getCantidadInventario() + $cantidad_inventario);

				$detalles = $item->getMarca() . ',' . $item->getModelo() . ',' . $item->getPrecioC() . ',' . $cantidad_inventario;
				$logs = $this->logsOb->generateLogs(null, null, $this->getUser(), 'addinventario', $detalles);
				$this->addFlash('success', "Se movieron {$cantidad_inventario} producto(s) al Inventario");
				$em->persist($logs);
				$em->flush();
			} else {
				$this->addFlash('error', "Estas intentando mover más productos de los que existen");
				return $this->redirectToRoute('app_taller_add_inventario', [
					'id' => $id
				]);
			}

			return $this->redirectToRoute('app_taller');
		}
		return $this->render("taller/add_inventario.html.twig", [
			'form' => $form->createView(),
			'cantidad_actual' => $item->getCantidadTaller()
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

	/**
	 * @Route("/user_factura/pdf/{id}", name="app_factura_detalles_pdf", requirements={"id"="\d+"})
	 */
	public function createPDF($id = null)
	{

		if ($id !== null) {
			$factura_repo = $this->getDoctrine()->getRepository(Facturas::class)->getFacturaById($id);
			$system = $this->getDoctrine()->getRepository(System::class)->find(1);
			$logsHistory = $this->getDoctrine()->getRepository(Logs::class)->getClientPays($id);
		} else {
			return $this->redirectToRoute('app_user_factura');
		}

		if (!$factura_repo) {
			return $this->redirectToRoute('app_user_factura');
		}

		$mpdf = new Mpdf();

		$mpdf->SetTitle('Factura 1 - ' . date_format($factura_repo[0]->getFecha(), 'd-m-Y'));

		$fecha = "<div style='text-align: right'>Fecha de Factura: " . date_format($factura_repo[0]->getFecha(), 'd-m-Y') . "</div>";
		$users = "<span style='margin-right: 20px'>Usuario: {$factura_repo[0]->getIdUser()}</span>";
		$users .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$users .= "<span style='margin-left: 20px'>Cliente: {$factura_repo[0]->getIdCliente()}</span>";

		$table = '<table width="100%" style="text-align: center;"><thead>';
		$table .= '<tr style="background: gainsboro"><th style="padding: 4px">Nombre</th><th>Tipo</th><th>Cantidad</th>';
		$table .= '<th>Precio</th><th>Subtotal</th></tr></thead><tbody>';

		$tmpColor = true;

		foreach ($factura_repo[0]->getProductos() as $producto) {
			$nombre = $producto->getIdProducto()->getMarca();
			$cantidad = $producto->getCantidad();
			$precio = $producto->getPrecio();
			$subtotal = $cantidad * $precio;
			if ($tmpColor == true) {
				$color = 'white';
				$tmpColor = false;
			} else {
				$color = 'whitesmoke';
				$tmpColor = true;
			}
			$table .= "<tr style='background: $color'><td style='padding: 4px;'>{$nombre}</td><td>Producto</td><td>{$cantidad}</td><td>{$precio}</td><td>{$subtotal}</td></tr>";
		}

		foreach ($factura_repo[0]->getServicios() as $servicio) {
			$nombre = $servicio->getIdServicio()->getName();
			$cantidad = $servicio->getCantidad();
			$precio = $servicio->getPrecio();
			$subtotal = $cantidad * $precio;
			if ($tmpColor == true) {
				$color = 'white';
				$tmpColor = false;
			} else {
				$color = 'whitesmoke';
				$tmpColor = true;
			}
			$table .= "<tr style='background: $color'><td style='padding: 4px;'>{$nombre}</td><td>Servicio</td><td>{$cantidad}</td><td>{$precio}</td><td>{$subtotal}</td></tr>";
		}
		$table .= '</tbody></table>';

		$descuento = $factura_repo[0]->getTotal() - $factura_repo[0]->getTotalReal();
		$style = "style='padding: 4px; border-bottom: 1px solid whitesmoke; width: 140px'";
		$style2 = "style='text-align: right; width: 140px; border-bottom: 1px solid whitesmoke'";

		$table_total = '<table>';
		$table_total .= "<tr><td {$style}>Subtotal</td><td {$style2}>{$factura_repo[0]->getTotal()}</td></tr>";
		$table_total .= "<tr><td {$style}>Descuento&nbsp;({$factura_repo[0]->getDescuento()}%)</td><td {$style2}>{$descuento}</td></tr>";
		$table_total .= "<tr><td {$style}>Total</td><td {$style2}>{$factura_repo[0]->getTotalReal()}</td></tr>";
		$table_total .= '</table>';

		$img = "<img src='dist/img/system/{$system->getImageName()}' width='150px' height='50px' style='margin-bottom: -40px'>";

		$mpdf->WriteHTML($img);
		$mpdf->WriteHTML($fecha);
		$mpdf->WriteHTML("<br><br>");
		$mpdf->WriteHTML($users);
		$mpdf->WriteHTML("<br>");
		$mpdf->WriteHTML($table);
		$mpdf->WriteHTML("<br>");
		$mpdf->WriteHTML($table_total);

		//return $this->render('test.html.twig');
		$mpdf->Output('factura.pdf', \Mpdf\Output\Destination::INLINE);
	}

}
