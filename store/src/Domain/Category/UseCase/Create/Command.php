<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      allowEmptyString = false
     * )
     */
    public $name;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      allowEmptyString = true
     * )
     */
    public $url;

    /**
     * @Assert\NotBlank()
     */
    public $parent;
}