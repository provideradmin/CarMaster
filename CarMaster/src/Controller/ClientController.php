<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/create-client', name: 'create_client')]
    public function createClient(EntityManagerInterface $entityManager): Response
    {
        $faker = Factory::create();

        $client = new Client(
            $faker->name,
            $faker->unique()->safeEmail,
            $faker->phoneNumber
        );

        $entityManager->persist($client);
        $entityManager->flush();

        return new JsonResponse(
            [
                'id' => $client->getId(),
                'name' => $client->getName(),
                'email' => $client->getEmail(),
                'phoneNumber' => $client->getPhone()
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
