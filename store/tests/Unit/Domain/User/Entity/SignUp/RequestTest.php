<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\User\Entity\SignUp;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Name;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $date = new DateTimeImmutable(),
            $name = new Name('First', 'Last'),
            $email = new Email('test@app.test'),
            $hash = 'hash',
            $token = 'token'
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($date, $user->getCreatedDate());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());

        self::assertTrue($user->getRole()->isUser());
    }
}