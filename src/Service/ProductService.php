<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductService as Service;
use App\Repository\ProductRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getAllProductsPaginated(int $page, array $filters = []): Paginator
    {
//        $products = [];
//        foreach ($this->productRepository->findByPage($page, $filters) as $product) {
//            $products[] = $product;
//        }
//        return $products;
        return $this->productRepository->findByPage($page, $filters);
    }

    public function getProductById(int $id): Product
    {
        $product = $this->productRepository->find($id);
        if ($product) {
            return $product;
        } else {
            throw new Exception('Product not found', 404);
        }
    }

    public function saveProduct(Product $product): Product
    {
        $this->productRepository->save($product, true);
        return $product;
    }

    public function updateProduct(Product $productUpdated, int $id): Product
    {
        $product = $this->productRepository->find($id);
        if ($product) {
            $product->setName($productUpdated->getName())
                ->setDescription($productUpdated->getDescription())
                ->setManufacturer($productUpdated->getManufacturer())
                ->setReleaseDate($productUpdated->getReleaseDate())
                ->setPriceByn($productUpdated->getPriceByn())
                ->setProductType($productUpdated->getProductType());
            $this->productRepository->save($product, true);
            return $product;
        } else {
            throw new Exception('Product not found', 404);
        }
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->productRepository->find($id);
        if ($product) {
            $this->productRepository->remove($product, true);
        } else {
            throw new Exception('Product not found', 404);
        }
    }

    public function addProductService(Product $product, Service $service): void
    {
        $product->addService($service);
        $this->productRepository->save($product, true);
    }

    public function updateProductService(Product $product, int $serviceId, int $price, int $term): void
    {
        foreach ($product->getServices() as $service) {
            if ($service->getId() == $serviceId) {
                $service->setTerm($term);
                $service->setPrice($price * 100);
                $this->productRepository->save($product, true);

                return;
            }
        }

        throw new Exception('Service not found', 404);

    }

    public function deleteProductService(Product $product, int $serviceId): void
    {
        foreach ($product->getServices() as $service) {
            if ($service->getId() == $serviceId) {
                $product->removeService($service);
                $this->productRepository->save($product, true);

                return;
            }
        }

        throw new Exception('Service not found', 404);
    }
}