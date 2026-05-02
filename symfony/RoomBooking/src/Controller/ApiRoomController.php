<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiRoomController extends AbstractController
{
    #[Route('/api/rooms', name: 'api_rooms_index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): JsonResponse
    {
        $rooms = $roomRepository->findBy([], ['id' => 'ASC']);
        $data = [];

        foreach ($rooms as $room) {
            $data[] = [
                'id' => $room->getId(),
                'name' => $room->getName(),
                'capacity' => $room->getCapacity(),
                'location' => $room->getLocation(),
                'isActive' => $room->isActive(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/api/rooms/{id}', name: 'api_room_id', methods: ['GET'])]
    public function room_id(RoomRepository $roomRepository, int $id): JsonResponse
    {
        $room = $roomRepository->find($id);
        $data = [];

        if ($room === null) {
            $data = ['error' => 'Room is not found'];
            return $this->json($data, 404);
        }

        $data = [
                'id' => $room->getId(),
                'name' => $room->getName(),
                'capacity' => $room->getCapacity(),
                'location' => $room->getLocation(),
                'isActive' => $room->isActive(),
        ];
        return $this->json($data);

    }
}
