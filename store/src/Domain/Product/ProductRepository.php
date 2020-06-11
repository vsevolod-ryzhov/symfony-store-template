<?php

declare(strict_types=1);


namespace App\Domain\Product;


use App\Domain\Product\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ProductRepository
{
    private const PRODUCT_NOT_FOUND_MESSAGE = 'Товар не найден.';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Product::class);
    }

    public function add(Product $product): void
    {
        $this->em->persist($product);
    }

    /**
     * @param int $id
     * @return Product
     * @throws EntityNotFoundException
     */
    public function get(int $id): Product
    {
        /** @var Product $product */
        if (!$product = $this->repository->find($id)) {
            throw new EntityNotFoundException(self::PRODUCT_NOT_FOUND_MESSAGE);
        }
        return $product;
    }

    public function hasByUrl(string $url): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.url = :url')
                ->setParameter(':url', $url)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasBySku(string $sku): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.sku = :sku')
                ->setParameter(':sku', $sku)
                ->getQuery()->getSingleScalarResult() > 0;
    }
}