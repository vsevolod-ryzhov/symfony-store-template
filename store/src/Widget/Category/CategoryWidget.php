<?php

declare(strict_types=1);


namespace App\Widget\Category;


use App\Domain\Category\CategoryRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryWidget extends AbstractExtension
{
    /**
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('product_category', [$this, 'category'], ['needs_environment' => true, 'is_safe' => ['html']])
        ];
    }

    public function category(Environment $twig, int $parent_id): string
    {
        $category = $this->repository->get($parent_id);
        return $twig->render('widget/categories/category.html.twig', [
            'category' => $category
        ]);
    }
}