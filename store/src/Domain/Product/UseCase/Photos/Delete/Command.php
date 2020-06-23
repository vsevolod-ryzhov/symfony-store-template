<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Photos\Delete;

use App\Domain\Product\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public $id;

    public $file;

    public function __construct(int $id, string $file)
    {
        $this->id = $id;
        $this->file = $file;
    }
}