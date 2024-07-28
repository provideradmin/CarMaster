<?php
declare(strict_types=1);


use App\Entity\Order;
use App\Entity\Client;
use App\Entity\Car;
use App\Entity\Service;
use App\Entity\Part;
use App\Entity\Material;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrderInitialization(): void
    {
        $order = new Order();

        $this->assertInstanceOf(\DateTime::class, $order->getCreationDate());
        $this->assertSame(0.0, $order->getTotalCost());
        $this->assertNull($order->getPaymentDate());
        $this->assertCount(0, $order->getServices());
        $this->assertCount(0, $order->getParts());
        $this->assertCount(0, $order->getMaterials());
    }

    public function testClientAssociation(): void
    {
        $order = new Order();
        $client = $this->createMock(Client::class);

        $order->setClient($client);
        $this->assertSame($client, $order->getClient());
    }

    public function testCarAssociation(): void
    {
        $order = new Order();
        $car = $this->createMock(Car::class);

        $order->setCar($car);
        $this->assertSame($car, $order->getCar());
    }

    public function testTotalCost(): void
    {
        $order = new Order();

        $order->setTotalCost(150.5);
        $this->assertSame(150.5, $order->getTotalCost());
    }

    public function testPaymentDate(): void
    {
        $order = new Order();
        $paymentDate = new \DateTime('2024-07-26');

        $order->setPaymentDate($paymentDate);
        $this->assertSame($paymentDate, $order->getPaymentDate());

        $order->setPaymentDate(null);
        $this->assertNull($order->getPaymentDate());
    }

    public function testAddRemoveServices(): void
    {
        $order = new Order();
        $service = $this->createMock(Service::class);

        $order->addService($service);
        $this->assertCount(1, $order->getServices());
        $this->assertTrue($order->getServices()->contains($service));

        $order->removeService($service);
        $this->assertCount(0, $order->getServices());
        $this->assertFalse($order->getServices()->contains($service));
    }

    public function testAddRemoveParts(): void
    {
        $order = new Order();
        $part = $this->createMock(Part::class);

        $order->addPart($part);
        $this->assertCount(1, $order->getParts());
        $this->assertTrue($order->getParts()->contains($part));

        $order->removePart($part);
        $this->assertCount(0, $order->getParts());
        $this->assertFalse($order->getParts()->contains($part));
    }

    public function testAddRemoveMaterials(): void
    {
        $order = new Order();
        $material = $this->createMock(Material::class);

        $order->addMaterial($material);
        $this->assertCount(1, $order->getMaterials());
        $this->assertTrue($order->getMaterials()->contains($material));

        $order->removeMaterial($material);
        $this->assertCount(0, $order->getMaterials());
        $this->assertFalse($order->getMaterials()->contains($material));
    }
    public function testGetId()
    {
        $order = new Order();

        $reflection = new \ReflectionClass($order);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($order, 123);

        $this->assertEquals(123, $order->getId());
    }
}
