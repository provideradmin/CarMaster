<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, OneToMany, Table};
use Symfony\Component\Serializer\Annotation\Ignore;

#[Entity]
#[Table(name: 'client')]
class Client
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[Column(length: 255)]
    private string $name;

    #[Column(length: 255)]
    private string $email;

    #[Column(length: 20)]
    private string $phone;

    #[OneToMany(mappedBy: 'client', targetEntity: Car::class, cascade: ['persist', 'remove'])]
    private Collection $cars;

    public function __construct(string $name, string $email, string $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
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
