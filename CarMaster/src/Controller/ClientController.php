<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Manager\ClientManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientManager $clientManager;
    private EntityManagerInterface $entityManager;

    public function __construct(ClientManager $clientManager, EntityManagerInterface $entityManager)
    {
        $this->clientManager = $clientManager;
        $this->entityManager = $entityManager;
    }

    #[Route('/clients', name: 'list_clients', methods: ['GET'])]
    public function listClients(): Response
    {
        $clients = $this->entityManager->getRepository(Client::class)->findAll();

        return $this->render('client/list.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/clients/{id}', name: 'get_client', methods: ['GET'])]
    public function getClient(int $id): Response
    {
        $client = $this->clientManager->getClient($id);

        if (!$client) {
            throw $this->createNotFoundException('Client not found');
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/create-client', name: 'create_client', methods: ['GET', 'POST'])]
    public function createClient(Request $request): Response
    {

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_clients');
        }

        return $this->render('client/create.html.twig',[
        'form' => $form->createView(),
        ]);
    }

    #[Route('/clients/{id}/edit', name: 'edit_client', methods: ['GET', 'POST'])]
    public function editClient(int $id, Request $request): Response
    {
        $client = $this->clientManager->getClient($id);

        if (!$client) {
            throw $this->createNotFoundException('Client not found');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_clients');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }

    #[Route('/clients/{id}/delete', name: 'delete_client', methods: ['POST'])]
    public function deleteClient(int $id): Response
    {
        $client = $this->clientManager->getClient($id);

        if (!$client) {
            throw $this->createNotFoundException('Client not found');
        }

        $this->clientManager->deleteClient($client);

        return $this->redirectToRoute('list_clients');
    }
}
