<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiReservationController extends AbstractController
{
    #[Route('/api/reservations', name: 'api_reservations', methods: ['GET'])]
    public function api_reservations(ReservationRepository $reservationRepository): JsonResponse
    {
        $reservations = $reservationRepository->findBy([], ['id' => 'ASC']);
        $data = [];
        foreach ($reservations as $reservation) {
            $data[] = [
                'id' => $reservation->getId(),
                'visitorName' => $reservation->getVisitorName(),
                'visitorEmail' => $reservation->getVisitorEmail(),
                'startsAt' => $reservation->getStartsAt()?->format('Y-m-d H:i:s'),
                'endsAt' => $reservation->getEndsAt()?->format('Y-m-d H:i:s'),
                'note' => $reservation->getNote(),
                'status' => $reservation->getStatus(),
                'createdAt' => $reservation->getCreatedAt()?->format('Y-m-d H:i:s'),
                'room' => [
                    'id' => $reservation->getRoom()->getId(),
                    'name' => $reservation->getRoom()->getName(),
                ],
                'ownerEmail' => $reservation->getUser()?->getEmail(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/api/reservations/{id}', name: 'api_reservation_detail', methods: ['GET'])]
    public function api_reservation_detail(int $id, ReservationRepository $reservationRepository): JsonResponse
    {
        $reservation = $reservationRepository->find($id);
        if ($reservation === null) {
            $data = [
                'error' => 'Reservation not found.'
            ];
            return $this->json($data, 404);
        }

        $data = [
            'id' => $reservation->getId(),
            'visitorName' => $reservation->getVisitorName(),
            'visitorEmail' => $reservation->getVisitorEmail(),
            'startsAt' => $reservation->getStartsAt()?->format('Y-m-d H:i:s'),
            'endsAt' => $reservation->getEndsAt()?->format('Y-m-d H:i:s'),
            'note' => $reservation->getNote(),
            'status' => $reservation->getStatus(),
            'createdAt' => $reservation->getCreatedAt()?->format('Y-m-d H:i:s'),
            'room' => [
                'id' => $reservation->getRoom()?->getId(),
                'name' => $reservation->getRoom()?->getName(),
            ],
            'ownerEmail' => $reservation->getUser()?->getEmail(),
        ];
        return $this->json($data);

    }
}
