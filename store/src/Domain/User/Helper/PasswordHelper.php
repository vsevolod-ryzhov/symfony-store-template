<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use RuntimeException;

class PasswordHelper
{
    const PASSWORD_CHARS_DICTIONARY = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';

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

    /**
     * Generate random password
     * @param int $length
     * @return string
     */
    public function generate($length = 6) {
        $code = "";
        $len = strlen(self::PASSWORD_CHARS_DICTIONARY);
        for ($i = 0; $i < $length; $i++)
        {
            $index = rand(0, $len - 1);
            $code .= self::PASSWORD_CHARS_DICTIONARY[$index];
        }

        return $code;
    }
}