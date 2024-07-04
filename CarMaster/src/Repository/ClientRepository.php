<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @return array
     */
    public function findAllClientData(): array
    {
        $clients = $this->findAll();
        return array_map(function ($client) {
            return $this->getClientData($client);
        }, $clients);
    }

    /**
     * @return array
     */
    public function findClientData(int $id): ?array
    {
        $client = $this->find($id);
        if (!$client) {
            return null;
        }

        return $this->getClientData($client);
    }

    private function getClientData(Client $client): array
    {
        return [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
        ];
    }
}
