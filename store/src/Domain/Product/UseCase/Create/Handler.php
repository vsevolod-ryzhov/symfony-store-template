<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Create;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
use App\Domain\Product\Entity\Product;
use App\Domain\Product\ProductQuery;
use App\Domain\Product\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Symfony\Component\String\Slugger\SluggerInterface;

class Handler
{
    const PRODUCT_EXISTS_MESSAGE = 'Товар с указанными данными уже существует';

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
        if ($this->repository->hasByUrl($command->url)) {
            throw new DomainException(self::PRODUCT_EXISTS_MESSAGE);
        }
        if ($this->repository->hasBySku($command->sku)) {
            throw new DomainException(self::PRODUCT_EXISTS_MESSAGE);
        }

        $name = $command->url ?: $command->name;
        $slug = $this->slugger->slug($name)->lower()->toString();

        $sort = $this->query->getMaxSort();
        $sort++;

        $product = Product::create(
            new DateTimeImmutable(),
            $command->name,
            $slug,
            $command->sku,
            new Price($command->price, $command->priceOld),
            $command->warehouse,
            $command->weight,
            $command->description,
            new Meta($command->metaName, $command->metaKeywords, $command->metaDescription),
            $sort
        );

        $this->repository->add($product);
        $this->em->flush();
    }
}