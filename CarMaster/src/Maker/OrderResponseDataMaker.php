<?php

declare(strict_types=1);

namespace App\Maker;

use App\Entity\Order;

class OrderResponseDataMaker
{
    public function build(Order $order, float $totalCost): array
    {
        return [
            'id' => $order->getId(),
            'client' => $order->getClient()->getName(),
            'car' => $order->getCar()->getBrand() . ' ' . $order->getCar()->getModel(),
            'creationDate' => $order->getCreationDate()->format('Y-m-d H:i:s'),
            'totalCost' => $totalCost,
            'services' => array_map(function ($service) {
                return ['name' => $service->getName(), 'cost' => $service->getCost()];
            }, $order->getServices()->toArray()),
            'parts' => array_map(function ($part) {
                return ['name' => $part->getName(), 'cost' => $part->getCost()];
            }, $order->getParts()->toArray()),
            'materials' => array_map(function ($material) {
                return ['name' => $material->getName(), 'cost' => $material->getCost()];
            }, $order->getMaterials()->toArray()),
        ];
    }
}
