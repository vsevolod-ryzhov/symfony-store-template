<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\SignUp\Request;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Name;
use App\Domain\User\Entity\User;
use App\Domain\User\Helper\PasswordHelper;
use App\Domain\User\Helper\TokenHelper;
use App\Domain\User\Service\TokenSender;
use App\Domain\User\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
    private const USER_EXISTS_MESSAGE = 'Пользователь с такими данными уже существует';

    private $em;
    private $repository;
    private $password;
    private $tokenGenerator;
    private $tokenSender;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repository,
        PasswordHelper $password,
        TokenHelper $tokenHelper,
        TokenSender $tokenSender
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->password = $password;
        $this->tokenGenerator = $tokenHelper;
        $this->tokenSender = $tokenSender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->repository->hasByEmail($email)) {
            throw new DomainException(self::USER_EXISTS_MESSAGE);
        }

        $user = User::signUpByEmail(
            new DateTimeImmutable(),
            new Name(
                $command->name,
                $command->surname
            ),
            $email,
            $command->phone,
            $this->password->hash($command->password),
            $token = $this->tokenGenerator->getToken()
        );

        $this->repository->add($user);

        $this->tokenSender->sendSignUpConfirm($email, $token);

        $this->em->flush();
    }
}