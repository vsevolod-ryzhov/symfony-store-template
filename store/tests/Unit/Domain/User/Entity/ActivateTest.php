<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity;


use App\Domain\User\Entity\User;
use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class ActivateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(User::STATUS_BLOCKED);

        $user->setStatus(User::STATUS_ACTIVE);

        self::assertTrue($user->isActive());
        self::assertFalse($user->isBlocked());
    }

    public function testAlready(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(User::STATUS_ACTIVE);

        $this->expectExceptionMessage('У пользователя уже установлен этот статус.');
        $user->setStatus(User::STATUS_ACTIVE);
    }
}