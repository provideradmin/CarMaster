<?php

namespace App\Controller;

use App\Manager\ClientManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientManager $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    #[Route('/create-client', name: 'create_client')]
    public function createClient(): JsonResponse
    {
        // Используем ClientManager для создания клиента
        $client = $this->clientManager->createClient();

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
