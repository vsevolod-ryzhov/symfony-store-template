<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\Change;


use App\Domain\User\Entity\Status;
use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        self::assertTrue($user->getStatus()->isWait());
        self::assertFalse($user->getStatus()->isActive());
        $user->setStatus(Status::active());
        self::assertTrue($user->getStatus()->isActive());
    }

    public function testFail(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(Status::active());
        $this->expectExceptionMessage('У пользователя уже установлен этот статус.');
        $user->setStatus(Status::active());
    }

    public function testBlock(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(new Status(Status::STATUS_BLOCKED));
        self::assertTrue($user->getStatus()->isBlocked());
        self::assertFalse($user->getStatus()->isActive());
    }
}