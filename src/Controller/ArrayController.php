<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/array')]
class ArrayController extends AbstractController
{
    #[Route('/users', name: '_array.users')]
    public function users(): Response
    {
        $users = [
            ['firstname' => 'Audrey', 'name' => 'Fcz', 'age' => 38 ],
            ['firstname' => 'Tessa', 'name' => 'Fcz', 'age' => 12 ],
            ['firstname' => 'Cassie', 'name' => 'Fcz', 'age' => 0 ]
        ];

        return $this->render('array/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/{nb?5<\d+>}', name: '_array')]
    public function index($nb): Response
    {
        $grades = [];
        for ($i = 0; $i < $nb; $i++) {
            $grades[] = rand(0, 20);
        }

        return $this->render('array/index.html.twig', [
            'grades' => $grades,
        ]);
    }
}
