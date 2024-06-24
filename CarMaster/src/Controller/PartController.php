<?php

namespace App\Controller;

use App\Entity\Part;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PartController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/parts', name: 'list_parts', methods: ['GET'])]
    public function listParts(): Response
    {
        $parts = $this->entityManager->getRepository(Part::class)->findAll();

        return $this->render('part/list.html.twig', [
            'parts' => $parts,
        ]);
    }

    #[Route('/parts/{id}', name: 'get_part', methods: ['GET'])]
    public function getPart(int $id): Response
    {
        $part = $this->entityManager->getRepository(Part::class)->find($id);

        if (!$part) {
            throw $this->createNotFoundException('Part not found');
        }

        return $this->render('part/show.html.twig', [
            'part' => $part,
        ]);
    }

    #[Route('/create-part', name: 'create_part', methods: ['GET', 'POST'])]
    public function createPart(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $cost = (float)$request->request->get('cost');
            $quantity = (int)$request->request->get('quantity');
            $sellingPrice = (float)$request->request->get('selling_price');

            $part = new Part($name, $cost, $quantity, $sellingPrice);

            $this->entityManager->persist($part);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_parts');
        }

        return $this->render('part/create.html.twig');
    }

    #[Route('/parts/{id}/edit', name: 'edit_part', methods: ['GET', 'POST'])]
    public function editPart(int $id, Request $request): Response
    {
        $part = $this->entityManager->getRepository(Part::class)->find($id);

        if (!$part) {
            throw $this->createNotFoundException('Part not found');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $cost = (float)$request->request->get('cost');
            $quantity = (int)$request->request->get('quantity');
            $sellingPrice = (float)$request->request->get('selling_price');

            $part->setName($name);
            $part->setCost($cost);
            $part->setQuantity($quantity);
            $part->setSellingPrice($sellingPrice);

            $this->entityManager->flush();

            return $this->redirectToRoute('list_parts');
        }

        return $this->render('part/edit.html.twig', [
            'part' => $part,
        ]);
    }

    #[Route('/parts/{id}/delete', name: 'delete_part', methods: ['POST'])]
    public function deletePart(int $id): Response
    {
        $part = $this->entityManager->getRepository(Part::class)->find($id);

        if (!$part) {
            throw $this->createNotFoundException('Part not found');
        }

        $this->entityManager->remove($part);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_parts');
    }
}
