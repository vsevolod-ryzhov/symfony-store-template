<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use App\Domain\User\Entity\ResetToken;
use DateInterval;
use DateTimeImmutable;
use Exception;
use RuntimeException;

class TokenHelper
{
    public function getToken(int $length = 32): string
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    public function getResetToken(string $interval = 'PT1H', int $length = 32): ResetToken
    {
        try {
            return new ResetToken(
                $this->getToken($length),
                (new DateTimeImmutable())->add(new DateInterval($interval))
            );
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}