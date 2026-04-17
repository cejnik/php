<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SessionSessionInterface;

class TaskController extends AbstractController
{
    #[Route("/", name:"task_list")]
    public function index(SessionSessionInterface $session)
    {
        $notes = $session->get('notes', []);
        return $this->render(
            'task/index.html.twig',
            [
        'notes' => $notes
        ]
        );
    }

    #[Route("/add", name: "task_add")]
    public function add_task(Request $request, SessionSessionInterface $session)
    {
        if ($request->isMethod('POST')) {
            $notes = $session->get('notes', []);
            $notes[] = $request->request->get('note');
            $session->set('notes', $notes);
            return $this->redirect('/');
        }
        return $this->render('task/add_task.html.twig');
    }

    #[Route("/delete/{id}", name: "task_delete")]
    public function delete_task(SessionSessionInterface $session, int $id)
    {
        $notes = $session ->get('notes', []);
        if (isset($notes[$id])) {
            unset($notes[$id]);
            $session->set("notes", $notes);
        }
        return $this->redirect('/');
    }

}
