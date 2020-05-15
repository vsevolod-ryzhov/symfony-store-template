<?php

declare(strict_types=1);


namespace App\Security;


use App\Domain\User\UserQuery;
use App\Domain\User\View\AuthView;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $userQuery;

    public function __construct(UserQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername(string $username)
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user, $username);
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . get_class($user));
        }

        $stored_user = $this->loadUser($user->getUsername());
        return self::identityByUser($stored_user, $user->getUsername());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return $class === UserIdentity::class;
    }

    private function loadUser($username): AuthView
    {
        if ($user = $this->userQuery->findForAuthByEmail($username)) {
            return $user;
        }

        throw new UsernameNotFoundException('');
    }

    private static function identityByUser(AuthView $user, string $username): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email ?: $username,
            $user->password_hash ?: '',
            $user->role,
            $user->status
        );
    }
}