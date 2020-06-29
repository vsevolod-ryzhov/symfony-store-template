<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Create;

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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      allowEmptyString = false
     * )
     */
    public $url;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      allowEmptyString = false
     * )
     */
    public $sku;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    public $price;

    /**
     * @Assert\PositiveOrZero
     */
    public $priceOld;

    /**
     * @Assert\NotBlank()
     * @Assert\PositiveOrZero
     */
    public $warehouse;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    public $weight;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $metaName;

    /**
     * @var string
     */
    public $metaKeywords;

    /**
     * @var string
     */
    public $metaDescription;
}