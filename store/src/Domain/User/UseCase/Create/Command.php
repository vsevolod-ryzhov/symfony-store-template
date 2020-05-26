<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=11, max=20)
     * @Assert\Regex(pattern=App\Domain\User\Entity\Phone::PHONE_REGEXP_PATTERN)
     */
    public $phone;

    /**
     * @Assert\NotBlank()
     */
    public $surname;

    /**
     * @Assert\NotBlank()
     */
    public $name;
}