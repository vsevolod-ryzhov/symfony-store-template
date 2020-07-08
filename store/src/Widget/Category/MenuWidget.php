<?php

declare(strict_types=1);


namespace App\Widget\Category;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\TwigFunction;

class MenuWidget extends \Twig\Extension\AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, RouterInterface $router)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('category_menu', [$this, 'menu'], ['needs_environment' => true, 'is_safe' => ['html']])
        ];
    }

    public function menu(Environment $twig): string
    {
        $repository = $this->em->getRepository(Category::class);
        $items = $repository->childrenHierarchy(
            $this->categoryRepository->getRoot(),
            false,
            [
                'decorate' => true,
                'nodeDecorator' => function ($node)
                {
                    $name = "";
                    if ($node['lvl'] > 1) {
                        $name = str_repeat(' ', $node['lvl'] - 1) . '&rdca;';
                    }
                    $name .= $node['name'];
                    $url = $this->router->generate('category.show', ['url' => $node['url']]);
                    return "<a href=\"$url\">$name</a>";
                }
            ],
            false
        );

        return $twig->render('widget/categories/menu.html.twig', [
            'items' => $items
        ]);
    }
}