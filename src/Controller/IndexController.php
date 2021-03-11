<?php

namespace App\Controller;

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
			$user->setVentaDirect(0);
			$user->setVentaIndirect(0);
			$user->setServicioDirect(0);
			$user->setServicioIndirect(0);
			$user->setPassword($passwordEncoder->encodePassword(
				$user,
				$form['password']->getData()
			));
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('app_home');

		}
		return $this->render('index/index.html.twig',
			['form' => $form->createView(), 'msg' => $user]
		);

	}
}
