<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\Product\Entity;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
use App\Domain\Product\Entity\Product;
use App\Tests\Factory\Product\TestProductFactory;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $product = (new TestProductFactory())->build();

        self::assertNotNull($product);
        self::assertTrue(is_a($product, Product::class));
        self::assertTrue(is_a($product->getMeta(), Meta::class));
        self::assertTrue(is_a($product->getPrice(), Price::class));
    }
}