<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[Entity]
#[Table(name: 'car')]
class Car
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    #[Groups(['car:read', 'car:write'])]
    private int $id;

    #[Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $type;

    #[Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $brand;

    #[Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $model;

    #[Column(type: 'integer')]
    #[Groups(['car:read', 'car:write'])]
    private int $year;

    #[Column(length: 20)]
    #[Groups(['car:read', 'car:write'])]
    private string $number;

    #[ManyToOne(targetEntity: Client::class, inversedBy: 'cars')]
    #[JoinColumn(name: 'client_id', referencedColumnName: 'id')]
    #[Groups(['car:read', 'car:write'])]
    #[Ignore] // Добавлено, чтобы избежать циклической зависимости
    private Client $client;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
