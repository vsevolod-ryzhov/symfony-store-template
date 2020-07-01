<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Sort;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ObjectRepository|NestedTreeRepository
     */
    private $repository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Category::class);
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(string $sortedInfo): void
    {
        $sortedInfo = json_decode($sortedInfo, true);
        $category = $this->categoryRepository->get((int)$sortedInfo['el']);
        if ($sortedInfo['prev'] !== null) {
            $prev = $this->categoryRepository->get((int)$sortedInfo['prev']);
            $this->repository->persistAsNextSiblingOf($category, $prev);
        } elseif ($sortedInfo['next'] !== null) {
            $next = $this->categoryRepository->get((int)$sortedInfo['next']);
            $this->repository->persistAsPrevSiblingOf($category, $next);
        } elseif ($sortedInfo['parent'] !== null) {
            $parent = $this->categoryRepository->get((int)$sortedInfo['parent']);
            $this->repository->persistAsFirstChildOf($category, $parent);
        }

        $this->em->flush();
    }
}