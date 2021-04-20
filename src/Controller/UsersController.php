<?php

namespace App\Controller;

use App\Entity\System;
use App\Entity\User;
use App\Form\AddUserType;
use App\Form\EditUserType;
use App\Form\PayUserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
	private $logsOb;

	public function __construct()
	{
		$this->logsOb = new LogsController();
	}

	/**
	 * @Route("", name="app_users")
	 */
	public function usersAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$user = new User();
		$form = $this->createForm(AddUserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$user->setUsername(strtolower($user->getUsername()));
			$user->setName(ucfirst($user->getName()));
			$user->setActive(1);
			$user->setRoles(['ROLE_USER']);
			$user->setPayV(0);
			$user->setPayS(0);
			$user->setPayTotal(0);
			$user->setPassword($passwordEncoder->encodePassword(
				$user, '12345'
			));
			$em->persist($user);
			try {
				$em->flush();
			} catch (UniqueConstraintViolationException $e) {
				//exeption
			}

			return $this->redirectToRoute('app_users');
		}
		//listar trabajadores en tabla
		$user_data = $this->getDoctrine()->getRepository(User::class);
		$data = $user_data->showAllUsers();

		return $this->render('users/users.html.twig', ['form' => $form->createView(), 'data' => $data]);
	}

	/**
	 * @Route("/delete/{id}", name="app_users_delete", requirements={"id"="\d+"})
	 */
	public function userDeleteAction($id = null)
	{

		if ($id !== null) {
			$this->getDoctrine()->getRepository(User::class)->deleteBy($id);
		}

		return $this->redirectToRoute('app_users');

	}

	/**
	 * @Route("/edit/{id}", name="app_users_edit", requirements={"id"="\d+"})
	 */
	public function userEditAction($id = null, Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		if ($id !== null) {
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository(User::class)->find($id);
		} else {
			return $this->redirectToRoute('app_users');
		}

		if (!$user) {
			return $this->redirectToRoute('app_users');
		}

		$form = $this->createForm(EditUserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$user->setPassword($passwordEncoder->encodePassword($user, '12345'));
			$em->persist($user);
			$em->flush();
			return $this->redirectToRoute('app_users');
		}

		return $this->render('users/edit.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route("/pay/{id}", name="app_pay_users", requirements={"id"="\d+"})
	 */
	public function payUsers($id = null, Request $request)
	{
		if ($id != null) {
			$user = $this->getDoctrine()->getRepository(User::class)->find($id);
		} else {
			return $this->redirectToRoute('app_pay_users', [
				'id' => $id
			]);
		}

		$form = $this->createForm(PayUserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$idform = $form->getData()->getId();
			if ($id == $idform) {
				if (is_numeric($request->get("cantidad-pagar"))) {
					$cantidad_pagar = $request->get("cantidad-pagar");
				} else {
					return $this->redirectToRoute('app_pay_users', [
						'id' => $id
					]);;
				}
			} else {
				return $this->redirectToRoute('app_pay_users', [
					'id' => $id
				]);
			}

			if ($user->getPayTotal() < 0) {
				$tmpTotal = $user->getPayTotal();
			} else {
				$tmpTotal = 0;
			}

			if ($cantidad_pagar >= 0 && $cantidad_pagar <= $user->getPayTotal() + $user->getPayV() + $user->getPayS()) {
				$user->setPayTotal($user->getPayTotal() + $user->getPayV() + $user->getPayS() - $cantidad_pagar);
				$user->setPayV(0);
				$user->setPayS(0);

				$system = $this->getDoctrine()->getRepository(System::class)->find(1);
				$system->setGanancia($system->getGanancia() - $tmpTotal);
				$em->persist($system);
				if ($tmpTotal < 0) {
					$detalles = $tmpTotal * -1;
					$logs = $this->logsOb->generateLogs(null, null, $user, 'deudapaga', $detalles);
					$em->persist($logs);
				}

			} else {
				return $this->redirectToRoute('app_pay_users', [
					'id' => $id
				]);
			}

			if ($cantidad_pagar > 0) {
				$logs = $this->logsOb->generateLogs(null, null, $user, 'salario', $cantidad_pagar);
				$em->persist($logs);
			}

			$em->persist($user);
			$em->flush();
		}

		return $this->render('users/payusers.html.twig', [
				'form' => $form->createView(),
				'user' => $user]
		);
	}

	/**
	 * @Route("/amortizar/{id}", name="app_amortizar_users", requirements={"id"="\d+"})
	 */
	public function amortizar($id = null, Request $request)
	{
		if ($id != null) {
			$user = $this->getDoctrine()->getRepository(User::class)->find($id);
		} else {
			return $this->redirectToRoute('app_amortizar_users', [
				'id' => $id
			]);
		};

		$form = $this->createForm(PayUserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$idform = $form->getData()->getId();
			if ($id == $idform) {
				if (is_numeric($request->get("cantidad-pagar"))) {
					$cantidad_pagar = $request->get("cantidad-pagar");
				} else {
					return $this->redirectToRoute('app_amortizar_users', [
						'id' => $id
					]);;
				}
			} else {
				return $this->redirectToRoute('app_amortizar_users', [
					'id' => $id
				]);
			}
			if ($user->getPayTotal() < 0) {
				$tmpTotal = $user->getPayTotal() * -1;
			} else {
				$tmpTotal = 0;
			}
			if ($cantidad_pagar > 0 && $cantidad_pagar <= $tmpTotal) {

				if ($tmpTotal - $cantidad_pagar > 0) {
					$paytmp = ($tmpTotal - $cantidad_pagar) * -1;
				} else {
					$paytmp = 0;
				}

				$user->setPayTotal($paytmp);

				$system = $this->getDoctrine()->getRepository(System::class)->find(1);
				$system->setGanancia($system->getGanancia() + $cantidad_pagar);

				$logs = $this->logsOb->generateLogs(null, null, $user, 'deudapaga', $cantidad_pagar);
				$em->persist($logs);
				$em->flush();
			} else {
				return $this->redirectToRoute('app_amortizar_users', [
					'id' => $id
				]);
			}

		}

		return $this->render('users/amortizar.html.twig', [
			'form' => $form->createView(),
			'user' => $user
		]);
	}
}
