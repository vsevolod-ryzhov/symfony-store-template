<?php

declare(strict_types=1);


namespace App\Domain\User\Entity;

use Webmozart\Assert\Assert;

class Status
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';

    private $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [
            self::STATUS_ACTIVE,
            self::STATUS_BLOCKED,
            self::STATUS_WAIT
        ]);

        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    public static function wait(): self
    {
        return new self(self::STATUS_WAIT);
    }

    public function isEqual(self $status): bool
    {
        return $this->getValue() === $status->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isWait(): bool
    {
        return $this->value === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->value === self::STATUS_BLOCKED;
    }
}