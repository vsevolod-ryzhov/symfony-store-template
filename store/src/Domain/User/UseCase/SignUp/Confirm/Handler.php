<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\SignUp\Confirm;


use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->repository->findByConfirmToken($command->token)) {
            throw new DomainException('Пользователь не найден.');
        }

        $user->confirmSignUp();

        $this->em->flush();
    }
}