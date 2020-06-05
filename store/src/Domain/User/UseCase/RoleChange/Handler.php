<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\RoleChange;


use App\Domain\User\Entity\Role;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private $users;
    private $em;

    public function __construct(UserRepository $users, EntityManagerInterface $em)
    {
        $this->users = $users;
        $this->em = $em;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get($command->id);

        $user->changeRole(new Role($command->role));

        $this->em->flush();
    }
}