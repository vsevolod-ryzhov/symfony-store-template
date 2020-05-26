<?php

declare(strict_types=1);


namespace App\Domain\User;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Phone;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserRepository
{
    private const USER_NOT_FOUND_MESSAGE = 'Пользователь не найден.';

    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @param string $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function get(string $id): User
    {
        /** @var User $user */
        if (!$user = $this->repository->find($id)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        return $user;
    }

    /**
     * @param Email $email
     * @return User
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->repository->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        return $user;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasByPhone(Phone $phone): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.phone = :phone')
                ->setParameter(':phone', $phone->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repository->findOneBy(['confirmToken' => $token]);
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByResetToken(string $token): ?User
    {
        return $this->repository->findOneBy(['resetToken.token' => $token]);
    }
}