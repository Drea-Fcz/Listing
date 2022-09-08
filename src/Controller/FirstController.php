<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }

    #[Route('/sayHello/{name}/{firstname}', name: '_hello')]
    public function sayHello($name, $firstname): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => $name,
            'firstname' => $firstname,
            'path' => '    ',
        ]);
    }

    #[Route("/multi/{entier1<\d+>}/{entier2<\d+>}",
        name: "_multiplication")]
    public function multiplication($entier1, $entier2)
    {
        $res = $entier1 * $entier2;
        return new Response("<h1>$res</h1>");
    }
}
