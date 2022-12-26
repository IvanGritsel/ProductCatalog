<?php

namespace App\Controller;

use App\Mapper\ProductMapper;
use App\Mapper\ProductServiceMapper;
use App\Service\ServiceService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    private ProductService $productService;
    private ServiceService $serviceService;

    public function __construct(ProductService $productService, ServiceService $serviceService)
    {
        $this->productService = $productService;
        $this->serviceService = $serviceService;
    }

    #[Route('/admin', name: 'admin.main', methods: ['GET'])]
    public function loadAdmin(array $additional = []): Response
    {
        $products = $this->productService->getAllProducts();
        $pServices = [];
        $services = $this->serviceService->getAllServices();
        foreach ($products as $product) {
            foreach ($product->getServices() as $service) {
                $pServices[] = $service;
            }
        }

        return $this->render('admin.html.twig', [
            'pageTitle' => 'Admin',
            'products' => $products,
            'productServices' => $pServices,
            'services' => $services,
            'additional' => $additional,
        ]);
    }

    #[Route('/admin/add/product', name: 'admin.new.product', methods: ['POST'])]
    public function addProduct(Request $request): Response
    {
        $productArray = $request->request->all();
        $product = ProductMapper::toEntity($productArray);
        $product = $this->productService->saveProduct($product);

        return $this->loadAdmin();
    }

    #[Route('/admin/update/product/{id}', name: 'admin.update.product', methods: ['PUT'])]
    public function updateProduct(Request $request, int $id): Response
    {
        $productArray = $request->request->all();
        $product = ProductMapper::toEntity($productArray);
        $product = $this->productService->updateProduct($product, $id);

        return $this->loadAdmin();
    }

    #[Route('/admin/add/service', name: 'admin.new.service', methods: ['POST'])]
    public function addService(Request $request): Response
    {
        $serviceArray = $request->request->all();
        $serviceArray['product'] = $this->productService->getProductById($serviceArray['productId']);
        $serviceArray['service'] = $this->serviceService->getServiceById($serviceArray['serviceId']);
        $service = ProductServiceMapper::toEntity($serviceArray);
        $this->productService->addProductService($serviceArray['product'], $service);

        return $this->loadAdmin();
    }

    #[Route('/admin/update/service/{productId}/{serviceId}', name: 'admin.update.service', methods: ['PUT'])]
    public function updateService(Request $request, int $productId, int $serviceId): Response
    {
        $serviceArray = $request->request->all();
        $product = $this->productService->getProductById($productId);
        $this->productService->updateProductService($product, $serviceId, $serviceArray['price'], $serviceArray['term']);

        return $this->loadAdmin();
    }

    #[Route('/admin/delete/product/{id}', name: 'admin.delete.product', methods: ['DELETE'])]
    public function deleteProduct(int $id): Response
    {
        $this->productService->deleteProduct($id);

        return $this->loadAdmin();
    }

    #[Route('/admin/delete/service/{productId}/{serviceId}', name: 'admin.delete.service', methods: ['DELETE'])]
    public function deleteService(int $productId, int $serviceId): Response
    {
        $product = $this->productService->getProductById($productId);
        $this->productService->deleteProductService($product, $serviceId);

        return $this->loadAdmin();
    }
}