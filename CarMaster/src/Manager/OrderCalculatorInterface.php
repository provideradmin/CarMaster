<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Order;

interface OrderCalculatorInterface
{
    public function calculateTotalCost(Order $order): float;
}
