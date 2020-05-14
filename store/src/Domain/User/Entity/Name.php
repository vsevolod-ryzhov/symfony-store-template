<?php

declare(strict_types=1);


namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Name
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $surname;

    public function __construct(string $name, string $surname)
    {
        Assert::notEmpty($name);
        Assert::notEmpty($surname);

        $this->name = $name;
        $this->surname = $surname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getFull(): string
    {
        return $this->name . ' ' . $this->surname;
    }
}