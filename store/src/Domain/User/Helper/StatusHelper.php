<?php

declare(strict_types=1);


namespace App\Domain\User\Helper;


use App\Domain\User\Entity\Status;

class StatusHelper
{
    public static function statusList(): array
    {
        return [
            Status::STATUS_WAIT => 'Ожидание',
            Status::STATUS_ACTIVE => 'Активный',
            Status::STATUS_BLOCKED => 'Заблокирован'
        ];
    }

    public static function statusName($status): string
    {
        return self::statusList()[$status];
    }
}