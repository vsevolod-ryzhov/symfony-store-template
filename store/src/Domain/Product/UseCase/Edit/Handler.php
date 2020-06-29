<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Edit;


use App\Domain\Category\CategoryRepository;
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

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $repository,
        ProductQuery $query,
        SluggerInterface $slugger,
        CategoryRepository $categoryRepository
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->query = $query;
        $this->slugger = $slugger;
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(Command $command): void
    {
        $product = $this->repository->get($command->id);

        $category = null;
        if (!empty($command->category)) {
            $category = $this->categoryRepository->get($command->category);
        }

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
            $command->isDeleted,
            $category
        );

        $this->em->flush();
    }
}