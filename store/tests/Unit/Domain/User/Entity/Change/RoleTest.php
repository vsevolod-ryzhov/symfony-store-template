<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\Change;

use App\Domain\User\Entity\Role;
use App\Tests\Factory\User\TestUserFactory;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->changeRole(Role::admin());

        self::assertFalse($user->getRole()->isUser());
        self::assertTrue($user->getRole()->isAdmin());
    }

    public function testFail(): void
    {
        $user = (new TestUserFactory())->viaEmail()->build();

        $user->changeRole(Role::admin());
        self::assertTrue($user->getRole()->isAdmin());
        $this->expectExceptionMessage('Эта роль уже установлена.');
        $user->changeRole(Role::admin());
    }
}