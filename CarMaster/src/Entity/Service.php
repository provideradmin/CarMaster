<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, Table};

#[Entity]
#[Table(name: 'service')]
class Service
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private readonly int $id;

    #[Column(length: 255)]
    private string $name;

    #[Column(type: Types::FLOAT)]
    private float $cost;

    #[Column(type: Types::INTEGER)]
    private int $duration;

    public function __construct(string $name, float $cost, int $duration)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->duration = $duration;
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

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
}
