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
    public $title;

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
     * @Assert\NotBlank()
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
    public $metaTitle;

    /**
     * @var string
     */
    public $metaKeywords;

    /**
     * @var string
     */
    public $metaDescription;
}