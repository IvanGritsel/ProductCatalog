<?php

namespace App\Controller;

use App\Service\AwsService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AwsController extends AbstractController
{
    private AwsService $awsService;

    public function __construct(AwsService $awsService)
    {
        $this->awsService = $awsService;
    }

    #[Route('/aws/export', name: 'aws.export', methods: ['GET'])]
    public function exportCatalog(): Response
    {
        try {
            $path = $this->awsService->exportCatalog();
            return new JsonResponse([
                'awsResource' => $path,
            ]);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'back' => 'admin.main',
            ]);
        }
    }
}