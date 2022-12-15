<?php

namespace App\Service;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Exception;

class ServiceService
{
    private ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllServices(): array
    {
        return $this->serviceRepository->findAll();
    }

    public function getServiceById(int $id): Service
    {
        $service = $this->serviceRepository->find($id);
        if ($service) {
            return $service;
        } else {
            throw new Exception('Service not found', 404);
        }
    }
}