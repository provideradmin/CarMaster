<?php

namespace App\Controller;

use App\Manager\ClientManager;
use App\Manager\MySerializeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientManager $clientManager;
    private MySerializeManager $serializeManager;

    public function __construct(ClientManager $clientManager, MySerializeManager $serializeManager)
    {
        $this->clientManager = $clientManager;
        $this->serializeManager = $serializeManager;
    }

    #[Route('/create-client', name: 'create_client')]
    public function createClient(): JsonResponse
    {
        // Используем ClientManager для создания клиента
        $client = $this->clientManager->createClient();

        // Возвращаем JsonResponse, где объект сериализуется через MySerializeManager
        return new JsonResponse(
            $this->serializeManager->serializeToJson($client),
            JsonResponse::HTTP_CREATED,
            [],
            true // Указывает, что переданный контент уже сериализован
        );
    }
}
