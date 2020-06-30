<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Create;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        CategoryRepository $repository
    )
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function handle(Command $command): void
    {
        $parent = $this->repository->get($command->parent);

        $product = Category::create($command->name, $parent);

        $this->repository->add($product);
        $this->em->flush();
    }
}