<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, OneToMany, Table};
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute as Serialize;

#[Entity]
#[Table(name: 'client')]
class Client
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    #[Serialize\Groups(['client:read', 'client:write'])]
    private int $id;

    #[Column(length: 255)]
    #[Serialize\Groups(['client:read', 'client:write'])]
    private string $name;

    #[Column(length: 255)]
    #[Serialize\Groups(['client:read', 'client:write'])]
    private string $email;

    #[Column(length: 20)]
    #[Serialize\Groups(['client:read', 'client:write'])]
    private string $phone;

    #[OneToMany(targetEntity: Car::class, mappedBy: 'client', cascade: ['persist', 'remove'])]
    #[Serialize\Groups(['client:never'])]
    private Collection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    #[Ignore]
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): void
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setClient($this);
        }
    }

    public function removeCar(Car $car): void
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
            $car->setClient(null);
        }
    }
}
