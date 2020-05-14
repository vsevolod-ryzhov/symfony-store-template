<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\SignUp\Confirm;


class Command
{
    /**
     * @var string
     */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}