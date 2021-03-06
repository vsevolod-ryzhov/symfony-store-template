<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Edit;

use App\Domain\Product\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

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
     * @var boolean
     */
    public $isDeleted;

    /**
     * @var int
     * @Assert\Positive()
     */
    public $sort;

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

    /**
     * @var string
     */
    public $category;

    /**
     * Command constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $category = $product->getCategory();
        $this->id = $product->getId();
        $this->name = $product->getName();
        $this->url = $product->getUrl();
        $this->sku = $product->getSku();
        $this->price = $product->getPrice()->getPrice();
        $this->priceOld = $product->getPrice()->getOldPrice();
        $this->warehouse = $product->getWarehouse();
        $this->weight = $product->getWeight();
        $this->description = $product->getDescription();
        $this->metaName = $product->getMeta()->getName();
        $this->metaKeywords = $product->getMeta()->getKeywords();
        $this->metaDescription = $product->getMeta()->getDescription();
        $this->isDeleted = $product->isDeleted();
        $this->sort = $product->getSort();
        $this->category = ($category !== null) ? (string)$category->getId() : '';
    }


}