<?php

namespace App\Controller;

use App\Entity\System;
use App\Form\SystemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SystemController extends AbstractController
{
    /**
     * @Route("/system", name="app_system")
     */
    public function index(Request $request): Response
    {
    	$system = $this->getDoctrine()->getRepository(System::class)->find(1);
    	$form = $this->createForm(SystemType::class, $system);
    	$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($system);
			$em->flush();
    	}

        return $this->render('system/index.html.twig', [
			'form' => $form->createView()
        ]);
    }

	public function systemSettings()
	{
		$settings = $this->getDoctrine()->getRepository(System::class)->find(1);
		return $settings;
    }
}
