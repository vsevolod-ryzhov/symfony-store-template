<?php

declare(strict_types=1);


namespace App\Domain\Category;


use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PDO;
use UnexpectedValueException;

class CategoryQuery
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

    /**
     * @return array
     */
    public function allList(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from('product_categories')
            ->orderBy('id', 'asc')
            ->execute();

        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Filter\CategoryIndex\Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter\CategoryIndex\Filter $filter, int $page, int $size, string $sort, string $direction = 'asc'): PaginationInterface
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name',
                'parent_id',
                'tree_root',
                'lvl',
                'lft',
                'rgt'
            )
            ->from('product_categories')
            ->orderBy('id', 'asc');

        // exclude root category
        $stmt->andWhere('parent_id IS NOT NULL');

        if ($filter->id) {
            $stmt->andWhere('id = :id');
            $stmt->setParameter(':id', $filter->id);
        }

        if ($filter->name) {
            $stmt->andWhere('name = :name');
            $stmt->setParameter(':name', $filter->name);
        }

        if ($filter->parent) {
            $stmt->andWhere('parent_id = :parent_id');
            $stmt->setParameter(':parent_id', $filter->parent);
        }

        if (!in_array($sort, ['id', 'name'], true)) {
            throw new UnexpectedValueException('Невозможно сортировать по полю ' . $sort);
        }

        $stmt->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($stmt, $page, $size);
    }

    /**
     * @return array
     */
    public function allTree(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name',
                'tree_root',
                'lvl',
                'lft',
                'rgt'
            )
            ->from('product_categories')
            ->orderBy('tree_root, lft', 'asc')
            ->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}