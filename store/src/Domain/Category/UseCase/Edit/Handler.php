<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Edit;


use App\Domain\Category\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

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
        if ($command->id === $command->parent) {
            throw new DomainException('Can\'t assign self category as parent');
        }

        $category = $this->repository->get($command->id);
        $parent = $this->repository->get($command->parent);

        $category->setParent($parent);

        $category->update($command->name);

        $this->em->flush();
    }
}