<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Faker\Factory;

class CarController extends AbstractController
{
    #[Route('/create-car', name: 'create_car')]
    public function createCar(EntityManagerInterface $entityManager): JsonResponse
    {
        $faker = Factory::create();

        // Получаем случайного клиента из базы
        $clientRepository = $entityManager->getRepository(Client::class);
        $clients = $clientRepository->findAll();

        if (count($clients) === 0) {
            return new JsonResponse(['error' => 'No clients found'], 404);
        }

        $client = $faker->randomElement($clients);

        $car = new Car();
        $car->setType($faker->randomElement(['Sedan', 'SUV', 'Hatchback']));
        $car->setBrand($faker->company());
        $car->setModel($faker->word());
        $car->setYear($faker->numberBetween(1990, 2024));
        $car->setNumber($faker->regexify('[A-Z]{3}[0-9]{3}'));
        $car->setClient($client);

        $entityManager->persist($car);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $car->getId(),
            'type' => $car->getType(),
            'brand' => $car->getBrand(),
            'model' => $car->getModel(),
            'year' => $car->getYear(),
            'number' => $car->getNumber(),
            'client' => $car->getClient()->getId(),
        ], 201);
    }
}
