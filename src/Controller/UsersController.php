<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
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
			$user->setVentaDirect(0);
			$user->setVentaIndirect(0);
			$user->setServicioDirect(0);
			$user->setServicioIndirect(0);
			$user->setPassword($passwordEncoder->encodePassword(
				$user, '12345'
			));
			$em->persist($user);
			$em->flush();

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
}
