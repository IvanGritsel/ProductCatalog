<?php

namespace App\Tests\Controller;

use App\Entity\Enum\ProductType;
use App\Entity\Product;
use App\Entity\ProductService as PService;
use App\Entity\Service;
use App\Service\ProductService;
use App\Service\ServiceService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private array $products;
    private Product $product;
    private array $services;

    public function setUp(): void
    {
        $product = new Product();
        $productWithService = new Product();
        $product->setName('name1')
            ->setId(1)
            ->setDescription('description1')
            ->setManufacturer('manufacturer1')
            ->setReleaseDate(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))
            ->setPriceByn(100)
            ->setProductType(ProductType::TV);
        $productWithService->setName('name2')
            ->setId(2)
            ->setDescription('description2')
            ->setManufacturer('manufacturer2')
            ->setReleaseDate(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))
            ->setPriceByn(100)
            ->setProductType(ProductType::TV);

        $productNoId = new Product();
        $productNoId->setName('name1')
            ->setDescription('description1')
            ->setManufacturer('manufacturer1')
            ->setReleaseDate(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))
            ->setPriceByn(100)
            ->setProductType(ProductType::TV);

        $service = new PService();

        $actualService = new Service();
        $actualService->setId(1);
        $actualService->setName('service_name');

        $service->setId(1);
        $service->setPrice(10);
        $service->setTerm(2);
        $service->setService($actualService);
        $service->setProduct($productWithService);

        $productWithService->addService($service);

        $this->products = [$product, $productWithService];
        $this->services = [$actualService];
        $this->product = $productNoId;
    }

    public function testLoadAdmin()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->request('GET', '/admin');

        self::assertResponseIsSuccessful();
    }

    public function testAddProduct()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('saveProduct')
            ->willReturn($this->product);
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);


        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('POST', '/admin/add/product', [
            'name' => $this->product->getName(),
            'description' => $this->product->getDescription(),
            'manufacturer' => $this->product->getManufacturer(),
            'priceByn' => $this->product->getPriceByn(),
            'releaseDate' => $this->product->getReleaseDate()->format('Y-m-d'),
            'productType' => $this->product->getProductType()->value,
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testUpdateProduct()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('updateProduct')
            ->willReturn($this->product);
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('PUT', '/admin/update/product/1', [
            'name' => $this->products[0]->getName(),
            'description' => $this->products[0]->getDescription(),
            'manufacturer' => $this->products[0]->getManufacturer(),
            'priceByn' => $this->products[0]->getPriceByn(),
            'releaseDate' => $this->products[0]->getReleaseDate()->format('Y-m-d'),
            'productType' => $this->products[0]->getProductType()->value,
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testAddService()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($this->products[0]);
        $productService->expects(self::once())
            ->method('addProductService');
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getServiceById')
            ->with(1)
            ->willReturn($this->services[0]);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('POST', '/admin/add/service', [
            'productId' => 1,
            'serviceId' => 1,
            'price' => 1,
            'term' => 1,
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testUpdateService()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($this->products[0]);
        $productService->expects(self::once())
            ->method('updateProductService');
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('PUT', '/admin/update/service/1/1', [
            'price' => 1,
            'term' => 1,
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testDeleteProduct()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('deleteProduct')
            ->with(1);
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('DELETE', '/admin/delete/product/1');

        self::assertResponseIsSuccessful();
    }

    public function testDeleteService()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);
        $productService->expects(self::once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($this->products[0]);
        $productService->expects(self::once())
            ->method('deleteProductService')
            ->with($this->products[0], 1);
        $serviceService = $this->createMock(ServiceService::class);
        $serviceService->expects(self::once())
            ->method('getAllServices')
            ->willReturn($this->services);

        $container->set(ProductService::class, $productService);
        $container->set(ServiceService::class, $serviceService);

        $crawler = $client->xmlHttpRequest('DELETE', '/admin/delete/service/1/1');

        self::assertResponseIsSuccessful();
    }
}