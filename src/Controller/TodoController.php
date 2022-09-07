<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function mysql_xdevapi\getSession;

class TodoController extends AbstractController
{
    #[Route('/todo', name: '_todo')]
    public function index(Request $request): Response
    {
        // afficher notre tableau de ToDo
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'achat'=> 'Acheter la clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'Corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "la liste Todo vient d'être initialisée");
        }

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }
    #[Route('/todo/add/{name}/{content}', name: '_todo.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse
    {
        // verifier si j'ai une session avec un todo
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $this->addFlash('error', "le nom ($name) existe déjà dans la liste");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "la tache a bie été ajouté dans la liste");
            }
        } else {
        $this->addFlash('error', 'il y un un problème dans la liste');
        }

        return $this->redirectToRoute('_todo');
    }

    #[Route('/todo/update/{name}/{content}', name: '_todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        // verifier si j'ai une session avec un todo
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "la tache a bien été modifié dans la liste");
            } else {
                $this->addFlash('error', "la tache demandé n'existe pas ");
            }
        } else {
            $this->addFlash('error', 'il y un un problème dans la liste');
        }

        return $this->redirectToRoute('_todo');
    }

    #[Route('/todo/delete/{name?}', name: '_todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "la tache a bien été supprimé dans la liste");
            } else {
                $session->remove('todos');
                $this->addFlash('success', "La liste a bien été supprimé ");
            }
        } else {
            $this->addFlash('error', 'il y un un problème dans la liste');
        }
        return $this->redirectToRoute('_todo');
    }
}
