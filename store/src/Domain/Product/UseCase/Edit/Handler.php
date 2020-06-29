<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Edit;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
use App\Domain\Product\ProductQuery;
use App\Domain\Product\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var ProductQuery
     */
    private $query;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(EntityManagerInterface $em, ProductRepository $repository, ProductQuery $query, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->query = $query;
        $this->slugger = $slugger;
    }

    public function handle(Command $command): void
    {
        $product = $this->repository->get($command->id);

        $name = $command->url ?: $command->name;
        $slug = $this->slugger->slug($name)->lower()->toString();

        $sort = $command->sort ?: $this->query->getMaxSort() + 1;

        $product->update(
            new DateTimeImmutable(),
            $command->name,
            $slug,
            $command->sku,
            new Price($command->price, $command->priceOld),
            $command->warehouse,
            $command->weight,
            $command->description,
            new Meta($command->metaName, $command->metaKeywords, $command->metaDescription),
            $sort,
            $command->isDeleted
        );

        $this->em->flush();
    }
}