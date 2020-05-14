<?php

declare(strict_types=1);


namespace App\Domain\User;


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
}