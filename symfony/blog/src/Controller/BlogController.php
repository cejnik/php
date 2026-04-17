<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/', name:'blog_list')]
    public function index(SessionInterface $session)
    {
        $posts = $session->get('posts', [['title' => 'Název', 'content' => 'Obsah', 'author' => 'Autor']]);
        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/add', name:'add_blog')]
    public function addBlog(SessionInterface $session, Request $request)
    {
        if ($request->isMethod('POST')) {
            $posts = $session->get('posts', []);
            $posts[] = [
                'title' => $request->request->get('title'),
                'content' => $request->request->get('content'),
                'author' => $request->request->get('author')
            ];
            $session->set('posts', $posts);
            return $this->redirectToRoute('blog_list');
        }
        return $this->render('blog/add.html.twig');

    }

    #[Route('/delete/{id}', name: 'delete_blog')]
    public function deleteBlog(SessionInterface $session, int $id, Request $request)
    {
        if ($request->isMethod('POST')) {
            $posts = $session->get('posts', []);
            if (isset($posts[$id])) {
                unset($posts[$id]);
                $posts = array_values($posts);
                $session->set('posts', $posts);
            }
            return $this->redirectToRoute('blog_list');

        }
        return $this->redirectToRoute('blog_list');
    }

}
