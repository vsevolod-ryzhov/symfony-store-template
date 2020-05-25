<?php

declare(strict_types=1);


namespace App\Domain\User\Entity;


use Webmozart\Assert\Assert;

class Phone
{
    const PHONE_REGEXP_PATTERN = '/^\+?([0-9]{1})([0-9]{3})([0-9]{6,7})$/';

    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::regex($value, self::PHONE_REGEXP_PATTERN);

        $this->value = str_replace('+', '', $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}