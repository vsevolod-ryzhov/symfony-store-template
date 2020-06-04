<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\SignUp;


use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->confirmSignUp();

        self::assertFalse($user->getStatus()->isWait());
        self::assertTrue($user->getStatus()->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->confirmSignUp();
        $this->expectExceptionMessage('Пользователь уже подтвержден.');
        $user->confirmSignUp();
    }
}