<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Reset\Request;


use App\Domain\User\Entity\Email;
use App\Domain\User\Helper\TokenHelper;
use App\Domain\User\Service\TokenSender;
use App\Domain\User\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private $em;
    private $repository;
    private $tokenGenerator;
    private $tokenSender;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repository,
        TokenHelper $tokenGenerator,
        TokenSender $sender
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenSender = $sender;
    }

    public function handle(Command $command): void
    {
        $user = $this->repository->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenGenerator->getResetToken(),
            new DateTimeImmutable()
        );

        $this->em->flush();

        $this->tokenSender->sendReset($user->getEmail(), $user->getResetToken());
    }
}