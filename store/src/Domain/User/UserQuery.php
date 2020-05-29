<?php

declare(strict_types=1);


namespace App\Domain\User;


use App\Domain\User\View\AuthView;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class UserQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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

    public function all(Filter\UserIndex\Filter $filter): array
    {
        $stmt = $this->connection->createQueryBuilder()
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

        if ($filter->email) {
            $stmt->andWhere($stmt->expr()->like('LOWER(email)', ':email'));
            $stmt->setParameter(':email', '%' . mb_strtolower($filter->email) . '%');
        }

        if ($filter->phone) {
            $stmt->andWhere('phone = :phone');
            $stmt->setParameter(':phone', $filter->phone);
        }

        if ($filter->surname) {
            $stmt->andWhere($stmt->expr()->like('LOWER(name_surname)', ':surname'));
            $stmt->setParameter(':surname', '%' . mb_strtolower($filter->surname) . '%');
        }

        if ($filter->name) {
            $stmt->andWhere($stmt->expr()->like('LOWER(name_ame)', ':name'));
            $stmt->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->status) {
            $stmt->andWhere('status = :status');
            $stmt->setParameter(':status', $filter->status);
        }

        if ($filter->role) {
            $stmt->andWhere('role = :role');
            $stmt->setParameter(':role', $filter->role);
        }

        return $stmt->execute()->fetchAll(FetchMode::ASSOCIATIVE);
    }
}