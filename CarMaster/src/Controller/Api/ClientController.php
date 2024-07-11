<?php

namespace App\Controller\Api;

use App\DTO\ClientDTO;
use App\DTO\ClientUpdateDTO;
use App\Entity\Client;
use App\Manager\ClientManager;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/clients')]
class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private ClientManager $clientManager;

    public function __construct(
        ClientRepository       $clientRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface    $serializer,
        ClientManager $clientManager
    )
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->clientManager = $clientManager;
    }

    #[Route('', methods: ['GET'])]
    public function getClients(): JsonResponse
    {
        $clients = $this->clientRepository->findAll();
        $json = $this->serializer->serialize($clients, 'json', [
            'groups' => ['client:read'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getClient(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($client,
            'json', [
                'groups' => ['client:read'],
            ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('', methods: ['POST'], format: 'json')]
    public function createClient(
        #[MapRequestPayload] ClientDTO   $clientDTO,
    ): JsonResponse
    {
        $client = $this->clientManager->createClientFromDTO($clientDTO);
        $json = $this->serializer->serialize($client, 'json', [
            'groups' => ['client:read'],
        ]);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function update(
        int                              $id,
        #[MapRequestPayload] ClientUpdateDTO   $clientUpdateDTO,
        ClientManager                    $clientManager,
    ): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }
        $json = $this->serializer->serialize($clientManager->updateClientFromDTO($client, $clientUpdateDTO), 'json', [
            'groups' => ['client:read'],
        ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
