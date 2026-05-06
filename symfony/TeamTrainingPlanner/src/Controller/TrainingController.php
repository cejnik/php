<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrainingController extends AbstractController
{
    #[Route('/trainings', name: 'app_training_index')]
    public function index(TrainingRepository $trainingRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $trainings = $trainingRepository->findBy([], ['id' => 'ASC']);
        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/trainings/new', name: 'app_training_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training->setCoach($this->getUser());
            $training->setCreatedAt(new \DateTimeImmutable());
            $training->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('app_training_index');
        }
        return $this->render('training/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trainings/{id}', name: 'app_training_detail')]
    public function detail(TrainingRepository $trainingRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $training = $trainingRepository->find($id);
        if ($training === null) {
            throw $this->createNotFoundException('Training not found');
        }
        return $this->render('training/training_detail.html.twig', [
            'training' => $training,
        ]);
    }

    #[Route('/trainings/{id}/edit', name: 'app_training_edit')]
    public function edit(TrainingRepository $trainingRepository, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $training = $trainingRepository->find($id);
        if ($training === null) {
            throw $this->createNotFoundException('Training not found');
        }
        $user = $this->getUser();
        if ($user !== $training->getCoach()) {
            throw $this->createAccessDeniedException('Access denied.');
        }
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $training->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            return $this->redirectToRoute('app_training_index');
        }
        return $this->render('training/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/trainings/{id}/delete', name: 'app_training_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingRepository $trainingRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_training_'.$id, $token)) {
            return $this->redirectToRoute('app_training_index');
        }

        $training = $trainingRepository->find($id);
        if ($training === null) {
            throw $this->createNotFoundException('Training not found');
        }

        $user = $this->getUser();
        if ($user !== $training->getCoach()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $entityManager->remove($training);
        $entityManager->flush();
        return $this->redirectToRoute('app_training_index');
    }


}
