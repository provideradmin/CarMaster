<?php

namespace App\Manager;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ClientManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createClient(): Client
    {
        $faker = Factory::create();

        $client = new Client(
            $faker->name,
            $faker->unique()->safeEmail,
            $faker->phoneNumber
        );

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}
