<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomTwigController extends AbstractController
{
    #[Route('/custom', name: 'app_custom_twig')]
    public function index(): Response
    {
        return $this->render('custom_twig/index.html.twig', [
            'controller_name' => 'CustomTwigController',
        ]);
    }


}
