<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ReservationRepository;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'index_reservation')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy([], ['id' => 'ASC']);
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
