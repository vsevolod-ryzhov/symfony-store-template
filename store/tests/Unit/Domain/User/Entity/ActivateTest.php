<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity;


use App\Domain\User\Entity\Status;
use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class ActivateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(new Status(Status::STATUS_BLOCKED));

        $user->setStatus(Status::active());

        self::assertTrue($user->getStatus()->isActive());
        self::assertFalse($user->getStatus()->isBlocked());
    }

    public function testAlready(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->setStatus(Status::active());

        $this->expectExceptionMessage('У пользователя уже установлен этот статус.');
        $user->setStatus(Status::active());
    }
}