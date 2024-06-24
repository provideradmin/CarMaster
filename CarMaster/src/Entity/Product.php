<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column,
    DiscriminatorColumn,
    DiscriminatorMap,
    Entity,
    GeneratedValue,
    Id,
    InheritanceType,
    Table};

#[Entity]
#[Table(name: 'product')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'type', type: Types::STRING)]
#[DiscriminatorMap(['part' => Part::class, 'material' => Material::class])]
abstract class Product
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING)]
    protected string $name;

    #[Column(type: Types::FLOAT)]
    protected float $cost;

    #[Column(type: Types::INTEGER)]
    protected int $quantity;

    public function __construct(string $name, float $cost, int $quantity)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->quantity = $quantity;
    }

    // Getters and setters...

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    // Business logic methods...

    public function addToInventory(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function removeFromInventory(int $quantity): void
    {
        if ($quantity <= $this->quantity) {
            $this->quantity -= $quantity;
        } else {
            throw new InventoryException('Недостаточное количество товара на складе.');
        }
    }

    // Common product data...

    public function getProductData(): array
    {
        return [
            'name' => $this->getName(),
            'quantity' => $this->getQuantity(),
            'cost' => $this->getCost()
        ];
    }
}