<?php

declare(strict_types=1);


namespace App\Domain\Product\Entity;


use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_products", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"url"}),
 *     @ORM\UniqueConstraint(columns={"sku"})
 * })
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="created_date")
     */
    private $createdDate;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="updated_date")
     */
    private $updatedDate;

    /**
     * @var string
     * @ORM\Column(type="string", name="title", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", name="url", length=255)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", name="sku", length=255)
     */
    private $sku;

    /**
     * @var Price
     * @ORM\Embedded(class="Price", columnPrefix="price_")
     */
    private $price;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="warehouse", length=255)
     */
    private $warehouse;

    /**
     * @var float
     * @ORM\Column(type="float", name="weight")
     */
    private $weight;

    /**
     * @var string
     * @ORM\Column(type="text", name="description", nullable=true)
     */
    private $description;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_deleted")
     */
    private $isDeleted;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @var Meta
     * @ORM\Embedded(class="Meta", columnPrefix="meta_")
     */
    private $meta;

    public static function create(
        DateTimeImmutable $createdDate,
        string $title,
        string $url,
        string $sku,
        Price $price,
        int $warehouse,
        float $weight,
        string $description,
        Meta $meta
    ): self
    {
        $product = new self();
        $product->createdDate = $createdDate;
        $product->updatedDate = $createdDate;
        $product->title = $title;
        $product->url = $url;
        $product->sku = $sku;
        $product->price = $price;
        $product->warehouse = $warehouse;
        $product->weight = $weight;
        $product->description = $description;
        $product->meta = $meta;
        $product->isDeleted = false;
        $product->sort = 1; // TODO: change it to calculated sort value
        return $product;
    }
}