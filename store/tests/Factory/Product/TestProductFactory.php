<?php

declare(strict_types=1);


namespace App\Tests\Factory\Product;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
use App\Domain\Product\Entity\Product;
use DateTimeImmutable;

class TestProductFactory
{
    public function build(): Product
    {
        $product = Product::create(
        new DateTimeImmutable(),
        "Test Product",
        "test_product",
        "test_sku",
        new Price(1000, 2000),
        10,
        1000,
        "Test Product description",
        new Meta("Test meta title", "test, meta, keywords", "test meta description"),
        1);

        return $product;
    }
}