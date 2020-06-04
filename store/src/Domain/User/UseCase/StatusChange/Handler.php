<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\StatusChange;


use App\Domain\User\Entity\Status;
use App\Domain\User\Helper\StatusHelper;
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

        if (!array_key_exists($command->status, StatusHelper::statusList())) {
            throw new DomainException('Недопустимый статус');
        }

        $user->setStatus(new Status($command->status));

        $this->em->flush();
    }
}