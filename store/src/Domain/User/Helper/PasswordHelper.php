<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use RuntimeException;

class PasswordHelper
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if ($hash === false) {
            throw new RuntimeException('Ошибка создания пароля.');
        }

        return $hash;
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}