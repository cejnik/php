<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/reservation/new', name: 'new_reservation')]
    public function new_reservation(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setStatus('confirmed');
            $reservation->setCreatedAt(new \DateTimeImmutable());
            $entityManagerInterface->persist($reservation);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('index_reservation');
        }
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
