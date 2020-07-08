<?php

declare(strict_types=1);


namespace App\Controller;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    private function getCategoriesForMenu()
    {
        $repository = $this->em->getRepository(Category::class);
        return $repository->childrenHierarchy(
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
                    $url = $this->generateUrl('category.show', ['url' => $node['url']]);
                    return "<a href=\"$url\">$name</a>";
                }
            ],
            false
        );
    }
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/home.html.twig', [
            'categories' => $this->getCategoriesForMenu()
        ]);
    }

    /**
     * @Route("/category/{url}", name="category.show")
     * @param Category $category
     * @return Response
     */
    public function category(Category $category): Response
    {
        return $this->render('app/home.html.twig', [
            'categories' => $this->getCategoriesForMenu()
        ]);
    }
}