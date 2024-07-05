<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private ClientRepository $clientRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ClientRepository $clientRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
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

        $json = $this->serializer->serialize($client, 'json', [
            'groups' => ['client:read'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function createClient(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $client = new Client();
        $client->setName($data['name']);
        $client->setEmail($data['email']);
        $client->setPhone($data['phone']);

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $json = $this->serializer->serialize($client, 'json', [
            'groups' => ['client:read'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
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

        if (isset($data['name'])) {
            $client->setName($data['name']);
        }
        if (isset($data['email'])) {
            $client->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $client->setPhone($data['phone']);
        }

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        $json = $this->serializer->serialize($client, 'json', [
            'groups' => ['client:read'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteClient(int $id): JsonResponse
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
