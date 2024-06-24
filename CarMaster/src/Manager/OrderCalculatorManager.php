<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Order;

class OrderCalculatorManager
{
    public function calculateTotalCost(Order $order): float
    {
        $totalCost = 0;

        foreach ($order->getServices() as $service) {
            $totalCost += $service->getCost();
        }

        foreach ($order->getParts() as $part) {
            $totalCost += $part->getCost();
        }

        foreach ($order->getMaterials() as $material) {
            $totalCost += $material->getCost();
        }

        return $totalCost;
    }
}
