<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\Reset;


use App\Domain\User\Entity\ResetToken;
use App\Tests\Factory\User\TestUserFactory;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset($now, $hash = 'hash');

        self::assertNull($user->getResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Время действия токена истекло.');
        $user->passwordReset($now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage('Запрос не был создан.');
        $user->passwordReset($now, 'hash');
    }
}