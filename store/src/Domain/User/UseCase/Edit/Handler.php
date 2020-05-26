<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Edit;


use App\Domain\User\Entity\Name;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function handle(Command $command): void
    {
        $user = $this->repository->get($command->id);

        $user->edit(new Name($command->name, $command->surname));
        $this->em->flush();
    }
}