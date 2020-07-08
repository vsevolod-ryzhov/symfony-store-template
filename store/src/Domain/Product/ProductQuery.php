<?php

declare(strict_types=1);


namespace App\Domain\Product;


use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

class ProductQuery
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    public function getMaxSort(): int
    {
        return (int)$this->connection->createQueryBuilder()
            ->select('COALESCE(MAX(sort), 1) AS sort')
            ->from('product_products', 'p')
            ->execute()->fetch()['sort'];
    }

    public function all(Filter\ProductIndex\Filter $filter, int $page, int $size, string $sort, string $direction = 'desc'): PaginationInterface
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'created_date',
                'updated_date',
                'name',
                'url',
                'sku',
                'price_price',
                'warehouse',
                'is_deleted'
            )
            ->from('product_products')
            ->orderBy('sort, created_date', 'desc');

        if ($filter->id) {
            $query->andWhere('id = :id');
            $query->setParameter(':id', $filter->id);
        }

        if (!in_array($sort, ['id', 'created_date', 'updated_date', 'name', 'url', 'sku', 'price_price', 'warehouse', 'is_deleted'], true)) {
            throw new UnexpectedValueException('Невозможно сортировать по полю ' . $sort);
        }

        $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($query, $page, $size);
    }

    public function byCategory(int $category_id, int $page, int $size): PaginationInterface
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'created_date',
                'updated_date',
                'name',
                'url',
                'sku',
                'price_price',
                'warehouse',
                'is_deleted'
            )
            ->from('product_products')
            ->orderBy('sort, created_date', 'desc');

        $query->andWhere('category_id = :id');
        $query->setParameter(':id', $category_id);

        return $this->paginator->paginate($query, $page, $size);
    }
}