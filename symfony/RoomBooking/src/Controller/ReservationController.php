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
use Symfony\Component\Form\FormError;

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
    public function new_reservation(Request $request, EntityManagerInterface $entityManagerInterface, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $startsAt = $reservation->getStartsAt();
            $endsAt = $reservation->getEndsAt();
            $room = $reservation->getRoom();

            if ($startsAt && $endsAt && $endsAt <= $startsAt) {
                $form->get('endsAt')->addError(new FormError('End time must be later than start time.'));
            } else {
                $conflictingReservation = $reservationRepository->findConflictingReservation($room, $startsAt, $endsAt);
                if ($conflictingReservation) {
                    $form->get('endsAt')->addError(new FormError('This room is already reserved for the selected time.'));
                }
            }

            if ($form->isValid()) {
                $reservation->setStatus('confirmed');
                $reservation->setCreatedAt(new \DateTimeImmutable());
                $entityManagerInterface->persist($reservation);
                $entityManagerInterface->flush();
                return $this->redirectToRoute('index_reservation');
            }
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/reservation/edit/{id}', name: 'edit_reservation')]
    public function edit_reservation(ReservationRepository $reservationRepository, int $id, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $reservation = $reservationRepository->find($id);
        if (!$reservation) {
            return $this->redirectToRoute('index_reservation');
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $startsAt = $reservation->getStartsAt();
            $endsAt = $reservation->getEndsAt();
            $room = $reservation->getRoom();

            if ($startsAt && $endsAt && $endsAt <= $startsAt) {
                $form->get('endsAt')->addError(new FormError('End time must be later than start time.'));
            } else {
                $conflictingReservation = $reservationRepository->findConflictingReservation($room, $startsAt, $endsAt, $reservation->getId());
                if ($conflictingReservation) {
                    $form->get('endsAt')->addError(new FormError('This room is already reserved for the selected time.'));
                }
            }
            if ($form->isValid()) {
                $entityManagerInterface->flush();
                return $this->redirectToRoute('index_reservation');
            }

        }
        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
