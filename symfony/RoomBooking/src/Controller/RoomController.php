<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class RoomController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findBy([], ['id' => 'ASC']);
        return $this->render('room/index.html.twig', [
            'rooms' => $rooms
        ]);
    }

    #[Route('/new', name: 'new_page')]
    public function action(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($room);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Your room is created.');
            return $this->redirectToRoute('index');

        }
        return $this->render('room/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_page')]
    public function edit_page(RoomRepository $roomRepository, int $id, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $room = $roomRepository->find($id);
        if (!$room) {
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Room was succesfully updated.');
            return $this->redirectToRoute('index');
        }

        return $this->render('room/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_page')]
    public function delete_page(int $id, EntityManagerInterface $entityManagerInterface, Request $request, RoomRepository $roomRepository): Response
    {
        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('index');
        }
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_room_'.$id, $token)) {
            $this->addFlash(
                'error',
                'Invalid token.'
            );
            return $this->redirectToRoute('index');
        }
        $room = $roomRepository->find($id);
        if (!$room) {
            return $this->redirectToRoute('index');
        } else {
            $entityManagerInterface->remove($room);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('index');
        }

    }
}
