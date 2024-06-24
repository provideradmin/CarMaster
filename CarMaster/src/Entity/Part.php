<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\{Column, Entity};
use Doctrine\DBAL\Types\Types;

#[Entity]
class Part extends Product
{
    #[Column(name:'selling_price', type: Types::FLOAT)]
    private float $sellingPrice;

    public function __construct(string $name, float $cost, int $quantity, float $sellingPrice)
    {
        parent::__construct($name, $cost, $quantity);
        $this->sellingPrice = $sellingPrice;
    }

    // Getters and setters...

    public function getSellingPrice(): float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(float $sellingPrice): void
    {
        $this->sellingPrice = $sellingPrice;
    }

    public function sell(int $quantity): void
    {
        $this->removeFromInventory($quantity);
    }

    public function getType(): string
    {
        return 'part';
    }
}