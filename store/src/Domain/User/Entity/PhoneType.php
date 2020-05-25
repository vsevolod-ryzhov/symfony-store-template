<?php

declare(strict_types=1);


namespace App\Domain\User\Entity;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PhoneType extends StringType
{
    public const NAME = 'user_user_phone';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Phone ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Phone($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}