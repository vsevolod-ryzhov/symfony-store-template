<?php

declare(strict_types=1);


namespace App\Tests\Factory\User;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Name;
use App\Domain\User\Entity\User;
use BadMethodCallException;
use DateTimeImmutable;

class TestUserFactory
{
    private $date;
    private $name;

    private $email;
    private $hash;
    private $token;
    private $confirmed;

    public function __construct()
    {
        $this->date = new DateTimeImmutable();
        $this->name = new Name('First', 'Last');
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function build(): User
    {
        if ($this->email) {
            $user = User::signUpByEmail(
                $this->date,
                $this->name,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }

            return $user;
        }

        throw new BadMethodCallException('Specify via method.');
    }
}