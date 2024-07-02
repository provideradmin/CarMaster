<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class MaterialController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/materials', name: 'list_materials', methods: ['GET'])]
    public function listMaterials(): Response
    {
        $materials = $this->entityManager->getRepository(Material::class)->findAll();

        return $this->render('material/list.html.twig', [
            'materials' => $materials,
        ]);
    }

    #[Route('/materials/{id}', name: 'get_material', methods: ['GET'])]
    public function getMaterial(int $id): Response
    {
        $material = $this->entityManager->getRepository(Material::class)->find($id);

        if (!$material) {
            throw $this->createNotFoundException('Material not found');
        }

        return $this->render('material/show.html.twig', [
            'material' => $material,
        ]);
    }

    #[Route('/create-material', name: 'create_material', methods: ['GET', 'POST'])]
    public function createMaterial(Request $request): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($material);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_materials');
        }

        return $this->render('material/create.html.twig',[
                'form' => $form->createView(),
        ]);
    }

    #[Route('/materials/{id}/edit', name: 'edit_material', methods: ['GET', 'POST'])]
    public function editMaterial(int $id, Request $request): Response
    {
        $material = $this->entityManager->getRepository(Material::class)->find($id);

        if (!$material) {
            throw $this->createNotFoundException('Material not found');
        }

        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($material);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_materials', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/materials/{id}/delete', name: 'delete_material', methods: ['POST'])]
    public function deleteMaterial(int $id): Response
    {
        $material = $this->entityManager->getRepository(Material::class)->find($id);

        if (!$material) {
            throw $this->createNotFoundException('Material not found');
        }

        $this->entityManager->remove($material);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_materials');
    }
}
