<?php

namespace App\Mapper;

use App\Entity\Enum\ProductType;
use App\Entity\Product;
use DateTime;

class ProductMapper
{
    public static function toEntity(array $toMap): Product
    {
        $product = new Product();
        return $product
            ->setName($toMap['name'])
            ->setDescription($toMap['description'])
            ->setManufacturer($toMap['manufacturer'])
            ->setReleaseDate(new DateTime($toMap['releaseDate']))
            ->setPriceByn($toMap['priceByn'] * 100)
            ->setProductType(ProductType::from($toMap['productType']));
    }
}