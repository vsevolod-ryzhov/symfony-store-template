<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Create;


use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
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
        $parent = $this->repository->get($command->parent);

        $url = $command->url ?: $command->name;
        $slug = $this->slugger->slug($url)->lower()->toString();

        $product = Category::create($command->name, $slug, $parent);

        $this->repository->add($product);
        $this->em->flush();
    }
}