<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Edit;


use App\Domain\Category\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(
        EntityManagerInterface $em,
        CategoryRepository $repository,
        SluggerInterface $slugger
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->slugger = $slugger;
    }

    public function handle(Command $command): void
    {
        if ($command->id === $command->parent) {
            throw new DomainException('Can\'t assign self category as parent');
        }

        $category = $this->repository->get($command->id);
        $parent = $this->repository->get($command->parent);

        $category->setParent($parent);

        $url = $command->url ?: $command->name;
        $slug = $this->slugger->slug($url)->lower()->toString();

        $category->update($command->name, $slug);

        $this->em->flush();
    }
}