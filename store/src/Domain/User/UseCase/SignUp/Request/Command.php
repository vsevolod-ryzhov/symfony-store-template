<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $surname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=11, max=20)
     * @Assert\Regex(pattern=App\Domain\User\Entity\Phone::PHONE_REGEXP_PATTERN)
     */
    public $phone;
}