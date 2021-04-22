<?php

namespace App\Controller;

use App\Form\ProfileUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
	/**
	 * @Route("/profile", name="app_profile")
	 */
	public function index($id = null, Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
	{
		$user = $this->getUser();

		$form = $this->createForm(ProfileUserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$user->setPassword($userPasswordEncoder->encodePassword(
				$user, $user->getPassword()
			));
			$em->persist($user);
			$em->flush();
			$this->addFlash('success', "Perfil actualizado");
		}

		return $this->render('profile/index.html.twig',
			['form' => $form->createView()]
		);
	}
}
