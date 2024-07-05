<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'car')]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $type;

    #[ORM\Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $brand;

    #[ORM\Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private string $model;

    #[ORM\Column(type: 'integer')]
    #[Groups(['car:read', 'car:write'])]
    private int $year;

    #[ORM\Column(length: 20)]
    #[Groups(['car:read', 'car:write'])]
    private string $number;

    #[Groups(['car:read', 'car:write'])]
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'cars')]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id')]
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

    #[Groups(['car:details'])]
    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
