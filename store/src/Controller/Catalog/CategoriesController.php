<?php

declare(strict_types=1);


namespace App\Controller\Catalog;


use App\Domain\Category\Entity\Category;
use App\Domain\Product\ProductQuery;
use App\Domain\Product\Service\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    private const PRODUCTS_PER_PAGE = 16;

    /**
     * @Route("/category/{url}", name="category.show")
     * @param Request $request
     * @param Category $category
     * @param ProductQuery $query
     * @param Image $image
     * @return Response
     */
    public function category(Request $request, Category $category, ProductQuery $query, Image $image): Response
    {
        $products = $query->byCategory(
            $category->getLft(),
            $category->getRgt(),
            $request->query->getInt('page', 1),
            self::PRODUCTS_PER_PAGE);
        return $this->render('app/front/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'image' => $image
        ]);
    }
}