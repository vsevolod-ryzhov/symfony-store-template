<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Edit;

use App\Domain\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $surname;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function createFromUser(User $user): self
    {
        $command = new self($user->getId());
        $command->surname = $user->getName()->getSurname();
        $command->name = $user->getName()->getName();
        return $command;
    }
}