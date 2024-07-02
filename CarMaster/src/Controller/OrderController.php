<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Manager\OrderCalculatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Builder\OrderResponseDataBuilder;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private OrderCalculatorInterface $orderCalculator;
    private OrderResponseDataBuilder $orderResponseDataBuilder;

    public function __construct(EntityManagerInterface $entityManager,
                                OrderCalculatorInterface $orderCalculator,
                                OrderResponseDataBuilder $orderResponseDataBuilder
    )
    {
        $this->entityManager = $entityManager;
        $this->orderCalculator = $orderCalculator;
        $this->orderResponseDataBuilder = $orderResponseDataBuilder;
    }

    #[Route('/orders', name: 'list_orders', methods: ['GET'])]
    public function listOrders(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return $this->render('order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/{id}', name: 'get_order', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOrder(int $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/json', name: 'get_order_json', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOrderJson(int $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $totalCost = $this->orderCalculator->calculateTotalCost($order);

        $orderData = $this->orderResponseDataBuilder->build($order, $totalCost);

        // Decode JSON to prevent escaping UTF-8 characters
        return $this->render('order/json.html.twig', [
            'orderData' => json_encode($orderData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);

    }
    #[Route('/orders/{id}/json/raw', name: 'get_order_json_raw', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOrderJsonRaw(int $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $totalCost = $this->orderCalculator->calculateTotalCost($order);

        $orderData = $this->orderResponseDataBuilder->build($order, $totalCost);

        return new JsonResponse($orderData, Response::HTTP_OK, [],);
    }

    #[Route('/orders/create', name: 'create_order', methods: ['GET', 'POST'])]
    public function createOrder(Request $request): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setTotalCost($this->orderCalculator->calculateTotalCost($order));

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_orders');
        }

        return $this->render('order/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orders/{id}/edit', name: 'edit_order', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editOrder(int $id, Request $request): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setTotalCost($this->orderCalculator->calculateTotalCost($order));

            $this->entityManager->flush();

            return $this->redirectToRoute('list_orders');
        }

        return $this->render('order/edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/delete', name: 'delete_order', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteOrder(int $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_orders');
    }
}
