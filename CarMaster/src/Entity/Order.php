<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, ManyToMany, Table};
use App\Entity\Service;
use App\Entity\Part;
use App\Entity\Material;

#[Entity]
#[Table(name: '`order`')]
class Order
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(name: 'creation_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $creationDate;

    #[Column(name: 'total_cost', type: Types::FLOAT)]
    private float $totalCost;

    #[Column(name: 'payment_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $paymentDate = null;

    #[ManyToOne(targetEntity: Client::class)]
    #[JoinColumn(name: 'client_id', nullable: false)]
    private Client $client;

    #[ManyToOne(targetEntity: Car::class)]
    #[JoinColumn(name: 'car_id', nullable: false)]
    private Car $car;

    #[ManyToMany(targetEntity: Service::class, inversedBy: 'orders')]
    private Collection $services;

    #[ManyToMany(targetEntity: Part::class, inversedBy: 'orders')]
    #[JoinTable(name: 'order_parts')]
    private Collection $parts;

    #[ManyToMany(targetEntity: Material::class, inversedBy: 'orders')]
    #[JoinTable(name: 'order_materials')]
    private Collection $materials;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->services = new ArrayCollection();
        $this->parts = new ArrayCollection();
        $this->materials = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    // Setter for creationDate is removed to prevent modifications

    public function getTotalCost(): float
    {
        return $this->totalCost;
    }

    public function setTotalCost(float $totalCost): void
    {
        $this->totalCost = $totalCost;
    }

    public function getPaymentDate(): ?\DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTime $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function setCar(Car $car): void
    {
        $this->car = $car;
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): void
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
        }
    }

    public function removeService(Service $service): void
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
        }
    }

    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(Part $part): void
    {
        if (!$this->parts->contains($part)) {
            $this->parts->add($part);
        }
    }

    public function removePart(Part $part): void
    {
        if ($this->parts->contains($part)) {
            $this->parts->removeElement($part);
        }
    }

    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): void
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
        }
    }

    public function removeMaterial(Material $material): void
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
        }
    }
}
