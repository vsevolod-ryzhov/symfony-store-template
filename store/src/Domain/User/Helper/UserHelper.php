<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use App\Domain\User\Entity\User;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => 'Ожидание',
            User::STATUS_ACTIVE => 'Активный',
            User::STATUS_BLOCKED => 'Заблокирован'
        ];
    }

    public static function statusName($status): string
    {
        return self::statusList()[$status];
    }
}