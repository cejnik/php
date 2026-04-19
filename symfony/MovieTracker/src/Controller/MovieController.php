<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MovieController extends AbstractController
{
    #[Route('/', name: 'app_movie')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findBy([], ['id' => 'ASC']);
        return $this->render('movie/index.html.twig', [
            "movies" => $movies
        ]);
    }
    #[Route('/new', name: 'new_movie')]
    public function new_movie(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($movie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Movie was created.');
            return $this->redirectToRoute('app_movie');

        }
        return $this->render('movie/new.html.twig', ['form' => $form->createView()]);

    }

    #[Route('/delete/{id}', name: 'delete_movie')]
    public function delete_movie(int $id, Request $request, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('app_movie');
        }
        $movie = $movieRepository->find($id);
        if (!$movie) {
            return $this->redirectToRoute('app_movie');
        } else {
            $entityManagerInterface->remove($movie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Movie was deleted.');
            return $this->redirectToRoute('app_movie');
        }
    }

    #[Route('/edit/{id}', name: 'edit_movie')]
    public function edit_movie(int $id, Request $request, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $movie = $movieRepository->find($id);

        if (!$movie) {
            return $this->redirectToRoute('app_movie');
        }
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Movie was updated.');
            return $this->redirectToRoute('app_movie');
        }
        return $this->render('movie/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/toggle/{id}', name: 'toggle_movie')]
    public function toggle_movie(int $id, Request $request, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {

        $movie = $movieRepository->find($id);
        if (!$movie) {
            return $this->redirectToRoute('app_movie');
        }
        if ($request->isMethod('POST')) {
            $movie->setWatched(!$movie->isWatched());
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Status was changed.');
            return $this->redirectToRoute('app_movie');
        }
        return $this->redirectToRoute('app_movie');

    }


}
