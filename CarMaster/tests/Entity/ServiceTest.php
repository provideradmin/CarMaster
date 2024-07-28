<?php
declare(strict_types=1);

namespace Entity;

use App\Entity\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testServiceCanBeCreated(): void
    {
        $service = new Service('Oil Change', 50.0, 30);

        $this->assertInstanceOf(Service::class, $service);
        $this->assertSame('Oil Change', $service->getName());
        $this->assertSame(50.0, $service->getCost());
        $this->assertSame(30, $service->getDuration());
    }

    public function testSettersAndGetters(): void
    {
        $service = new Service('Oil Change', 50.0, 30);

        // Test setName and getName
        $service->setName('Brake Inspection');
        $this->assertSame('Brake Inspection', $service->getName());

        // Test setCost and getCost
        $service->setCost(75.5);
        $this->assertSame(75.5, $service->getCost());

        // Test setDuration and getDuration
        $service->setDuration(45);
        $this->assertSame(45, $service->getDuration());
    }

    public function testGetId(): void
    {
        $service = new Service('Oil Change', 50.0, 30);

        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);

        $property->setValue($service, 1);
        $this->assertSame(1, $service->getId());
    }
}
