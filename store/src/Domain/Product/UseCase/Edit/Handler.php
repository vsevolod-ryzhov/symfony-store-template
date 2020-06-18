<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Edit;


use App\Domain\Product\Entity\Meta;
use App\Domain\Product\Entity\Price;
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
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(EntityManagerInterface $em, ProductRepository $repository, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->slugger = $slugger;
    }

    public function handle(Command $command): void
    {
        $product = $this->repository->get($command->id);

        if (!empty($command->url)) {
            $slug = $this->slugger->slug($command->url)->lower()->toString();
        } else {
            $slug = $this->slugger->slug($command->title)->lower()->toString();
        }

        $product->update(
            new DateTimeImmutable(),
            $command->title,
            $slug,
            $command->sku,
            new Price($command->price, $command->priceOld),
            $command->warehouse,
            $command->weight,
            $command->description,
            new Meta($command->metaTitle, $command->metaKeywords, $command->metaDescription)
        );

        $this->em->flush();
    }
}