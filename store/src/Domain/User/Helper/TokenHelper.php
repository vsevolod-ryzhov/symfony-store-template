<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


class TokenHelper
{
    public function getToken(int $length = 32): string
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}