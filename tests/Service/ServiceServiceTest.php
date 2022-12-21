<?php

namespace App\Tests\Service;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use App\Service\ServiceService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceServiceTest extends KernelTestCase
{
    private array $services;
    private Service $service;

    public function setUp(): void
    {
        $service = new Service();
        $this->service = $service;
        $this->services = [$service, new Service()];
    }

    public function testGetAll()
    {
        self::bootKernel();
        $container = self::getContainer();

        $serviceRepository = $this->createMock(ServiceRepository::class);
        $serviceRepository->expects(self::once())
            ->method('findAll')
            ->willReturn($this->services);

        $container->set(ServiceRepository::class, $serviceRepository);
        $serviceService = $container->get(ServiceService::class);

        self::assertEquals($this->services, $serviceService->getAllServices());
    }

    public function testGetById()
    {
        self::bootKernel();
        $container = self::getContainer();

        $serviceRepository = $this->createMock(ServiceRepository::class);
        $serviceRepository->expects(self::once())
            ->method('find')
            ->with(1)
            ->willReturn($this->service);

        $container->set(ServiceRepository::class, $serviceRepository);
        $serviceService = $container->get(ServiceService::class);

        self::assertEquals($this->service, $serviceService->getServiceById(1));
    }

    public function testGetByIdException()
    {
        self::bootKernel();
        $container = self::getContainer();

        $serviceRepository = $this->createMock(ServiceRepository::class);
        $serviceRepository->expects(self::once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        $container->set(ServiceRepository::class, $serviceRepository);
        $serviceService = $container->get(ServiceService::class);

        $this->expectException(Exception::class);
        $serviceService->getServiceById(2);
    }
}