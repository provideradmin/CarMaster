<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CarTypeController extends AbstractController
{
    #[Route('/car-type/{cartype}', name: 'car_type', methods: ['GET'])]
    public function carType(string $cartype, CarRepository $carRepository): JsonResponse
    {
        // Найдите машины по типу
        $cars = $carRepository->findByType($cartype);

        // Создайте массив для JSON-ответа
        $data = [];
        foreach ($cars as $car) {
            $data[] = [
                'id' => $car->getId(),
                'type' => $car->getType(),
                'brand' => $car->getBrand(),
                'model' => $car->getModel(),
                'year' => $car->getYear(),
                'number' => $car->getNumber(),
                'client' => [
                    'id' => $car->getClient()->getId(),
                    'name' => $car->getClient()->getName(),
                    'email' => $car->getClient()->getEmail(),
                    'phone' => $car->getClient()->getPhone(),
                ]
            ];
        }

        return new JsonResponse($data);
    }
}
