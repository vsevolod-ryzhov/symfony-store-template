<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Create;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Name;
use App\Domain\User\Entity\Phone;
use App\Domain\User\Entity\User;
use App\Domain\User\Helper\PasswordHelper;
use App\Domain\User\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
    private const USER_EXISTS_MESSAGE = 'Пользователь с такими данными уже существует';

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var PasswordHelper
     */
    private $passwordHelper;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, PasswordHelper $passwordHelper, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->passwordHelper = $passwordHelper;
        $this->em = $em;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);
        if ($this->repository->hasByEmail($email)) {
            throw new DomainException(self::USER_EXISTS_MESSAGE);
        }
        $phone = new Phone($command->email);
        if ($this->repository->hasByPhone($phone)) {
            throw new DomainException(self::USER_EXISTS_MESSAGE);
        }

        $user = User::create(
            new DateTimeImmutable(),
            new Name($command->name, $command->surname),
            $email,
            $phone,
            $this->passwordHelper->hash($this->passwordHelper->generate())
        );

        $this->repository->add($user);
        $this->em->flush();
    }
}