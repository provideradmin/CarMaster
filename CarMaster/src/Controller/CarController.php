<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/cars', name: 'list_cars', methods: ['GET'])]
    public function listCars(): Response
    {
        $cars = $this->entityManager->getRepository(Car::class)->findAll();

        return $this->render('car/list.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/cars/{id}', name: 'get_car', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getCar(int $id): Response
    {
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }

        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/create-car', name: 'create_car', methods: ['GET', 'POST'])]
    public function createCar(Request $request): Response
    {
        $car = new Car();

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($car);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_cars');
        }

        return $this->render('car/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cars/{id}/edit', name: 'edit_car', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editCar(int $id, Request $request): Response
    {
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('list_cars');
        }

        return $this->render('car/edit.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

    #[Route('/cars/{id}/delete', name: 'delete_car', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCar(int $id): Response
    {
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }

        $this->entityManager->remove($car);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_cars');
    }
}
