<?php

namespace App\Controller\Api;

use App\Manager\ClientManager;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/clients')]
class ClientController extends AbstractController
{
    private ClientManager $clientManager;
    private ClientRepository $clientRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ClientManager $clientManager,
        ClientRepository $clientRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->clientManager = $clientManager;
        $this->clientRepository = $clientRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('', methods: ['GET'])]
    public function getClients(): JsonResponse
    {
        $clients = $this->clientRepository->findAllClientData();
        $json = $this->serializer->serialize($clients, 'json', ['groups' => ['client:read']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getClient(int $id): JsonResponse
    {
        $client = $this->clientRepository->findClientData($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $this->serializer->serialize($client, 'json', ['groups' => ['client:read']]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function createClient(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $client = $this->clientManager->createClient($data);

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $clientData = $this->clientRepository->getClientData($client);
        $json = $this->serializer->serialize($clientData, 'json', ['groups' => ['client:read']]);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function updateClient(int $id, Request $request): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $client = $this->clientManager->updateClient($client, $data);

        $clientData = $this->clientRepository->getClientData($client);
        $json = $this->serializer->serialize($clientData, 'json', ['groups' => ['client:read']]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteClient(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $this->clientManager->deleteClient($client);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
