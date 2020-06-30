<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Sort;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository|\Gedmo\Tree\Entity\Repository\NestedTreeRepository
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

    private function getParent(Category $category): Category
    {
        $parent = $category->getParent();
        if ($parent === null) {
            throw new DomainException('Root category is not editable');
        }

        return $parent;
    }

    public function handle(string $sortedCategoryList): void
    {
        // TODO: this works only for one level changes in tree
//        $sortedCategoryList = json_decode($sortedCategoryList, true);
//        $parent = null;
//        foreach ($sortedCategoryList as $item) {
//            $category = $this->categoryRepository->get((int)$item);
//            if ($parent === null) {
//                $parent = $this->getParent($category);
//                $this->repository->persistAsFirstChildOf($category, $parent);
//            } else {
//                $this->repository->persistAsLastChildOf($category, $parent);
//            }
//        }
//        $this->em->flush();
    }
}