<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity;


use App\Domain\User\Entity\User;
use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(User::STATUS_BLOCKED);

        self::assertFalse($user->isActive());
        self::assertTrue($user->isBlocked());
    }

    public function testAlready(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(User::STATUS_BLOCKED);

        $this->expectExceptionMessage('У пользователя уже установлен этот статус.');
        $user->setStatus(User::STATUS_BLOCKED);
    }
}