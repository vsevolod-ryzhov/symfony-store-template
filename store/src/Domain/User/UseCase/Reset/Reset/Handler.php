<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Reset\Reset;


use App\Domain\User\Helper\PasswordHelper;
use App\Domain\User\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
    private $em;
    private $userRepository;
    private $passwordHelper;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        PasswordHelper $passwordHelper
    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordHelper = $passwordHelper;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->userRepository->findByResetToken($command->token)) {
            throw new DomainException('Не найден токен.');
        }

        $user->passwordReset(
            new DateTimeImmutable(),
            $this->passwordHelper->hash($command->password)
        );

        $this->em->flush();
    }
}