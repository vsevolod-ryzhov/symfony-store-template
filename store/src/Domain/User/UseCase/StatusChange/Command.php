<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\StatusChange;

class Command
{
    public $id;

    public $status;

    public function __construct(string $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
}