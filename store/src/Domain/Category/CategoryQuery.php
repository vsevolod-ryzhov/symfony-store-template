<?php

declare(strict_types=1);


namespace App\Domain\Category;


use Doctrine\DBAL\Connection;
use PDO;

class CategoryQuery
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
     * @return array
     */
    public function all(): array
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
            ->orderBy('id', 'asc')
            ->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}