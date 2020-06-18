<?php

declare(strict_types=1);


namespace App\Widget;


use App\Domain\Product\Entity\Meta;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MetaWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('meta', [$this, 'meta'], ['needs_environment' => true, 'is_safe' => ['html']])
        ];
    }

    public function meta(Environment $twig, Meta $meta): string {
        return $twig->render('widget/meta.html.twig',[
            'meta' => $meta
        ]);
    }
}