<?php

namespace App\Controller;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServiceController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/services', name: 'list_services', methods: ['GET'])]
    public function listServices(): Response
    {
        $services = $this->entityManager->getRepository(Service::class)->findAll();

        return $this->render('service/list.html.twig', [
            'services' => $services,
        ]);
    }

    #[Route('/services/{id}', name: 'get_service', methods: ['GET'])]
    public function getService(int $id): Response
    {
        $service = $this->entityManager->getRepository(Service::class)->find($id);

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/create-service', name: 'create_service', methods: ['GET', 'POST'])]
    public function createService(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $cost = (float)$request->request->get('cost');
            $duration = (int)$request->request->get('duration');

            $service = new Service($name, $cost, $duration);

            $this->entityManager->persist($service);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_services');
        }

        return $this->render('service/create.html.twig');
    }

    #[Route('/services/{id}/edit', name: 'edit_service', methods: ['GET', 'POST'])]
    public function editService(int $id, Request $request): Response
    {
        $service = $this->entityManager->getRepository(Service::class)->find($id);

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $cost = (float)$request->request->get('cost');
            $duration = (int)$request->request->get('duration');

            $service->setName($name);
            $service->setCost($cost);
            $service->setDuration($duration);

            $this->entityManager->flush();

            return $this->redirectToRoute('list_services');
        }

        return $this->render('service/edit.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/services/{id}/delete', name: 'delete_service', methods: ['POST'])]
    public function deleteService(int $id): RedirectResponse
    {
        $service = $this->entityManager->getRepository(Service::class)->find($id);

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        $this->entityManager->remove($service);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_services');
    }
}
