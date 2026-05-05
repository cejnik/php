<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrainingController extends AbstractController
{
    #[Route('/trainings', name: 'app_training_index')]
    public function index(TrainingRepository $trainingRepository): Response
    {
        $trainings = $trainingRepository->findBy([], ['id' => 'ASC']);
        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/trainings/{id}', name: 'app_training_detail')]
    public function detail(TrainingRepository $trainingRepository, int $id): Response
    {
        $training = $trainingRepository->find($id);
        if ($training === null) {
            throw $this->createNotFoundException('Training not found');
        }
        return $this->render('training/training_detail.html.twig', [
            'training' => $training,
        ]);
    }
}
