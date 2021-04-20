<?php

namespace App\Controller;

use App\Entity\System;
use App\Entity\User;
use App\Form\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IndexController extends AbstractController
{
	/**
	 * @Route("/index", name="app_index")
	 */
	public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$user_data = $this->getDoctrine()->getRepository(User::class);
		$data = $user_data->showAllUsers();

		$settings = new System();
		$settings->setPagename("Inventario");
		$settings->setWinproduct(0);
		$settings->setWinservice(0);
		$settings->setInversion(0);
		$settings->setRecuperado(0);
		$settings->setGanancia(0);
		$settings->setCaja(0);
		$settings->setBanco(0);
		$settings->setGastos(0);
		$settings->setYearStart(date('Y'));

		if ($data) {
			return $this->redirectToRoute('app_login');
		}

		$user = new User();
		$form = $this->createForm(AdminUserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$user->setUsername(strtolower($user->getUsername()));
			$user->setName(ucfirst($user->getName()));
			$user->setActive(1);
			$user->setRoles(['ROLE_ADMIN']);
			$user->setPayV(0);
			$user->setPayS(0);
			$user->setPayTotal(0);
			$user->setPassword($passwordEncoder->encodePassword(
				$user,
				$form['password']->getData()
			));
			$em->persist($user);
			$em->persist($settings);
			$em->flush();

			return $this->redirectToRoute('app_home');

		}
		return $this->render('index/index.html.twig',
			['form' => $form->createView(), 'msg' => $user]
		);

	}
}
