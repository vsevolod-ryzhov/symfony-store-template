<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\Reset;


use App\Domain\User\Entity\ResetToken;
use App\Tests\Factory\User\TestUserFactory;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Запрос уже отправлен.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new DateTimeImmutable();

        $user = (new TestUserFactory())->viaEmail()->confirmed()->build();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testNotConfirmed(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new TestUserFactory())->viaEmail()->build();

        $this->expectExceptionMessage('Пользователь не активирован.');
        $user->requestPasswordReset($token, $now);
    }
}