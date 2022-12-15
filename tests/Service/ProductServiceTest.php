<?php

namespace App\Tests\Service;

use App\Entity\Enum\ProductType;
use App\Entity\Product;
use App\Entity\ProductService as Service;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductServiceTest extends KernelTestCase
{
    private array $products;
    private Product $product1;
    private Product $product2;
    private Product $product3;
    private Product $productWithService;

    public function setUp(): void
    {
        $product1 = new Product();
        $product2 = new Product();
        $product3 = new Product();
        $productWithService = new Product();
        $product3->setName('name')
            ->setDescription('description')
            ->setManufacturer('manufacturer')
            ->setReleaseDate(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))
            ->setPriceByn(100)
            ->setProductType(ProductType::TV);
        $productWithService->setName('name')
            ->setDescription('description')
            ->setManufacturer('manufacturer')
            ->setReleaseDate(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))
            ->setPriceByn(100)
            ->setProductType(ProductType::TV);

        $service = new Service();
        $service->setId(1);
        $productWithService->addService($service);

        $this->products = [$product1, $product2];
        $this->product1 = $product1;
        $this->product2 = $product2;
        $this->product3 = $product3;
        $this->productWithService = $productWithService;
    }

    public function testGetAll()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('findAll')
            ->willReturn($this->products);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertEquals($this->products, $productService->getAllProducts());
    }

    public function testGetById()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(1)
            ->willReturn($this->product1);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertEquals($this->product1, $productService->getProductById(1));
    }

    public function testGetByIdException()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(4)
            ->willReturn(null);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        $this->expectException(Exception::class);
        $productService->getProductById(4);
    }

    public function testSave()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('save')
            ->with($this->product1);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertEquals($this->product1, $productService->saveProduct($this->product1));
    }

    public function testUpdate()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(2)
            ->willReturn($this->product2);
        $productRepository->expects(self::once())
            ->method('save')
            ->with($this->product3);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertEquals($this->product3, $productService->updateProduct($this->product3, 2));
    }

    public function testUpdateException()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(4)
            ->willReturn(null);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        $this->expectException(Exception::class);
        $productService->updateProduct($this->productWithService, 4);
    }

    public function testDelete()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(1)
            ->willReturn($this->product1);
        $productRepository->expects(self::once())
            ->method('remove')
            ->with($this->product1);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertNull($productService->deleteProduct(1)); // Ignore warning, works as intended
    }

    public function testDeleteException()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('find')
            ->with(4)
            ->willReturn(null);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        $this->expectException(Exception::class);
        $productService->deleteProduct(4);
    }

    public function testAddProductService()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('save')
            ->with($this->productWithService);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertNull($productService->addProductService($this->productWithService, new Service()));
    }

    public function testUpdateProductService()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('save')
            ->with($this->productWithService);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertNull($productService->updateProductService($this->productWithService, 1, 1, 1));
    }

    public function testUpdateProductServiceException()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productService = $container->get(ProductService::class);
        $this->expectException(Exception::class);

        $productService->updateProductService($this->productWithService, 3, 1, 1);
    }

    public function testDeleteProductService()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects(self::once())
            ->method('save')
            ->with($this->productWithService);

        $container->set(ProductRepository::class, $productRepository);
        $productService = $container->get(ProductService::class);

        self::assertNull($productService->deleteProductService($this->productWithService, 1));
    }

    public function testDeleteProductServiceException()
    {
        self::bootKernel();
        $container = static::getContainer();

        $productService = $container->get(ProductService::class);
        $this->expectException(Exception::class);

        $productService->deleteProductService($this->productWithService, 3);
    }
}
