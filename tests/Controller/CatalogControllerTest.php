<?php

namespace App\Tests\Controller;

use App\Entity\Enum\ProductType;
use App\Entity\Product;
use App\Entity\ProductService as PService;
use App\Entity\Service;
use App\Service\ProductService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatalogControllerTest extends WebTestCase
{
    private array $products;
    private Product $product;

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
        $this->product = $productWithService;
    }

    public function testLoadCatalog()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getAllProducts')
            ->willReturn($this->products);

        $container->set(ProductService::class, $productService);

        $crawler = $client->request('GET', '/catalog');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'name1');
    }

    public function testLoadProductPage()
    {
        $client = static::createClient();
        $container = self::getContainer();

        $productService = $this->createMock(ProductService::class);
        $productService->expects(self::once())
            ->method('getProductById')
            ->with(2)
            ->willReturn($this->product);

        $container->set(ProductService::class, $productService);

        $crawler = $client->request('GET', '/catalog/product/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'name2');
        $this->assertSelectorTextContains('label', 'service_name');
    }
}