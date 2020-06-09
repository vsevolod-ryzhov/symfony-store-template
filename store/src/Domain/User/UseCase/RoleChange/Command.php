<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\RoleChange;


use App\Domain\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $role;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromUser(User $user): self
    {
        $command = new self($user->getId());
        $command->role = $user->getRole()->getName();
        return $command;
    }
}