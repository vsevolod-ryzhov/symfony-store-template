<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Name;
use App\Domain\User\Entity\Role;
use App\Domain\User\Entity\User;
use App\Domain\User\Helper\PasswordHelper;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    /**
     * @var PasswordHelper
     */
    private $passwordHelper;

    public function __construct(PasswordHelper $passwordHelper)
    {
        $this->passwordHelper = $passwordHelper;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user = User::signUpByEmail(
            new DateTimeImmutable(),
            new Name('Admin', 'Admin'),
            new Email('admin@store.dev'),
            '+79210000000',
            $this->passwordHelper->hash('password'),
            'token'
        );
        $user->confirmSignUp();
        $user->changeRole(Role::admin());

        $manager->persist($user);
        $manager->flush();
    }
}