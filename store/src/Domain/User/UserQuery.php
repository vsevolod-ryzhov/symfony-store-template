<?php

declare(strict_types=1);


namespace App\Domain\User;


use App\Domain\User\View\AuthView;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;
use function in_array;

class UserQuery
{
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

    public function findEmailBySignUpConfirmToken(string $token): ?string
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('email')
            ->from('user_users')
            ->where('confirm_token = :token')
            ->setParameter(':token', $token)
            ->execute();

        $result = $stmt->fetch(FetchMode::COLUMN);

        return $result ?: null;
    }

    public function findForAuthByEmailOrPhone(string $email_or_phone): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'phone',
                'password_hash',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('phone = :username')
            ->orWhere('email = :username')
            ->setParameter(':username', $email_or_phone)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (*)')
                ->from('user_users')
                ->where('reset_token_token = :token')
                ->setParameter(':token', $token)
                ->execute()->fetchColumn(0) > 0;
    }

    public function all(Filter\UserIndex\Filter $filter, int $page, int $size, string $sort, string $direction = 'desc'): PaginationInterface
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'created_date',
                'name_surname',
                'name_name',
                'email',
                'phone',
                'role',
                'status'
            )
            ->from('user_users')
            ->orderBy('created_date', 'desc');

        if ($filter->id) {
            $query->andWhere('id = :id');
            $query->setParameter(':id', $filter->id);
        }

        if ($filter->created_date) {
            $query->andWhere('DATE(created_date) = :created_date');
            $query->setParameter(':created_date', date('Y-m-d', strtotime($filter->created_date)));
        }

        if ($filter->email) {
            $query->andWhere($query->expr()->like('LOWER(email)', ':email'));
            $query->setParameter(':email', '%' . mb_strtolower($filter->email) . '%');
        }

        if ($filter->phone) {
            $query->andWhere('phone = :phone');
            $query->setParameter(':phone', $filter->phone);
        }

        if ($filter->surname) {
            $query->andWhere($query->expr()->like('LOWER(name_surname)', ':surname'));
            $query->setParameter(':surname', '%' . mb_strtolower($filter->surname) . '%');
        }

        if ($filter->name) {
            $query->andWhere($query->expr()->like('LOWER(name_ame)', ':name'));
            $query->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->status) {
            $query->andWhere('status = :status');
            $query->setParameter(':status', $filter->status);
        }

        if ($filter->role) {
            $query->andWhere('role = :role');
            $query->setParameter(':role', $filter->role);
        }

        if (!in_array($sort, ['id', 'created_date', 'email', 'phone', 'surname', 'name', 'role', 'status'], true)) {
            throw new UnexpectedValueException('Невозможно сортировать по полю ' . $sort);
        }

        $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($query, $page, $size);
    }
}