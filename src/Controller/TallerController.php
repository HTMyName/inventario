<?php

namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
			['data' => $data]
		);
    }
}
