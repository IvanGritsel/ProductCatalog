<?php

namespace App\Controller;

use App\Entity\CurrencyConversions;
use App\Service\CurrencyConversionsService;
use App\Service\ProductService;
use Cassandra\Date;
use DateTime;
use GuzzleHttp\Client;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    private ProductService $productService;
    private CurrencyConversionsService $conversionsService;

    public function __construct(ProductService $productService, CurrencyConversionsService $conversionsService)
    {
        $this->productService = $productService;
        $this->conversionsService = $conversionsService;
    }

    #[Route('/catalog', name: 'catalog.main', methods: ['GET'])]
    public function loadCatalog(): Response
    {
        return $this->redirectToRoute('catalog.main.paginated', ['page' => 1]);
    }

    #[Route('/catalog/page/{page}', name: 'catalog.main.paginated', methods: ['GET'])]
    public function loadPaginatedCatalog(Request $request, int $page): Response
    {
        $filters = $request->query->all();
        $products = $this->productService->getAllProductsPaginated($page, $filters);
        return $this->render('catalog.html.twig', [
            'pageTitle' => 'Catalog',
            'products' => $products,
            'total' => $products->count(),
            'page' => $page,
            'conversionRates' => $this->loadConversionRates(),
        ]);
    }

    #[Route('/catalog/product/{id}', name: 'catalog.product', methods: ['GET'])]
    public function loadProductPage(int $id): Response
    {
        $product = $this->productService->getProductById($id);
        return $this->render('product.html.twig', [
            'pageTitle' => $product->getName(),
            'product' => $product,
            'conversionRates' => $this->loadConversionRates(),
        ]);
    }

    private function loadConversionRates(): array
    {
        $conversions = $this->conversionsService->getCurrentRates();
        if ($conversions) {
            return $conversions->getRates();
        } else {
            $client = new Client();
            $res = $client->getAsync('https://bankdabrabyt.by/export_courses.php')->wait();
            $parsedXml = new SimpleXMLElement($res->getBody());

            $conversions = $this->conversionsService->saveRates($parsedXml);
            return $conversions->getRates();
        }
    }
}