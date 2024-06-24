<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createClient(array $data): Client
    {
        $client = new Client(
            $data['name'],
            $data['email'],
            $data['phone']
        );

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }

    public function updateClient(Client $client, array $data): Client
    {
        $client->setName($data['name']);
        $client->setEmail($data['email']);
        $client->setPhone($data['phone']);

        $this->entityManager->flush();

        return $client;
    }

    public function deleteClient(Client $client): void
    {
        // Удаление клиента вместе с его машинами
        foreach ($client->getCars() as $car) {
            $this->entityManager->remove($car);
        }
        $this->entityManager->remove($client);
        $this->entityManager->flush();
    }

    public function getClient(int $id): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->find($id);
    }
}
