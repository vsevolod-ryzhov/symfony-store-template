<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use App\Domain\User\Entity\Role;

class RoleHelper
{
    public static function rolesList(): array
    {
        return [
            Role::ADMIN => 'Администратор',
            Role::USER => 'Пользователь'
        ];
    }

    public static function roleLabel($role): string
    {
        return self::rolesList()[$role];
    }
}