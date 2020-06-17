<?php

declare(strict_types=1);


namespace App\Widget\Product;


use App\Domain\Product\Entity\Price;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PriceWidget extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('product_price', [$this, 'price'], ['needs_environment' => true, 'is_safe' => ['html']])
        ];
    }

    public function price(Environment $twig, Price $price): string
    {
        return $twig->render('widget/products/price.html.twig', [
            'price' => $price
        ]);
    }
}