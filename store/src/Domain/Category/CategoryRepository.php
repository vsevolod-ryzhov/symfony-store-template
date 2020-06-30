<?php

declare(strict_types=1);


namespace App\Domain\Category;


use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

class CategoryRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Category::class);
    }

    /**
     * @param int $id
     * @return Category
     * @throws EntityNotFoundException
     */
    public function get(int $id): Category
    {
        /** @var Category $category */
        if (!$category = $this->repository->find($id)) {
            throw new EntityNotFoundException('Category not found');
        }
        return $category;
    }

    public function getRoot(): Category
    {
        /** @var Category $category */
        if (!$category = $this->repository->findOneBy(['parent' => null])) {
            throw new EntityNotFoundException('Category not found');
        }
        return $category;
    }

    /**
     * @param Category $category
     */
    public function add(Category $category): void
    {
        $this->em->persist($category);
    }
}