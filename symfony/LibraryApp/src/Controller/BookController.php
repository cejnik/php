<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/new', name: 'book_new')]
    public function book_new(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($book);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_book');
        }
        return $this->render('book/new.html.twig', ['form' => $form->createView()]);


    }
    #[Route('/delete/{id}', name: 'book_delete')]
    public function book_delete(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('app_book');
        }
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->redirectToRoute('app_book');
        } else {
            $entityManagerInterface->remove($book);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_book');
        }

    }

    #[Route('/edit/{id}', name: 'book_edit')]
    public function book_edit(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->redirectToRoute('app_book');
        }
        $form = $this->createForm(BookType::class, $book);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_book');
        }
        return $this->render('book/edit.html.twig', ['form' => $form->createView()]);
    }

}
