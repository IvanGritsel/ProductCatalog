<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/catalog', name: 'catalog.main', methods: ['GET'])]
    public function loadCatalog(): Response
    {
        return $this->render('catalog.html.twig', [
            'pageTitle' => 'Catalog',
            'products' => $this->productService->getAllProducts(),
        ]);
    }

    #[Route('/catalog/page/{page}', name: 'catalog.main.paginated', methods: ['GET'])]
    public function loadPaginatedCatalog(Request $request, int $page): Response
    {
        $filters = $request->query->all();
//        return new JsonResponse($this->productService->getAllProductsPaginated($page, $filters));
        $products = $this->productService->getAllProductsPaginated($page, $filters);
        return $this->render('catalog.html.twig', [
            'pageTitle' => 'Catalog',
            'products' => $products,
            'total' => $products->count(),
            'page' => $page,
        ]);
    }

    #[Route('/catalog/product/{id}', name: 'catalog.product', methods: ['GET'])]
    public function loadProductPage(int $id): Response
    {
        $product = $this->productService->getProductById($id);
        return $this->render('product.html.twig', [
            'pageTitle' => $product->getName(),
            'product' => $product,
        ]);
    }
}