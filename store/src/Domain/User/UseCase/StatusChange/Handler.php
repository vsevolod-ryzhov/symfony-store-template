<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\StatusChange;


use App\Domain\User\Helper\UserHelper;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

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
        $this->em = $em;
        $this->repository = $repository;
    }

    public function handle(Command $command) {
        $user = $this->repository->get($command->id);

        if (!array_key_exists($command->status, UserHelper::statusList())) {
            throw new DomainException('Недопустимый статус');
        }

        $user->setStatus($command->status);

        $this->em->flush();
    }
}