<?php

namespace App\Mapper;

use App\Entity\ProductService;

class ProductServiceMapper
{
    public static function toEntity(array $toMap): ProductService
    {
        $service = new ProductService();
        return $service
            ->setProduct($toMap['product'])
            ->setService($toMap['service'])
            ->setPrice($toMap['price'] * 100)
            ->setTerm($toMap['term']);
    }
}