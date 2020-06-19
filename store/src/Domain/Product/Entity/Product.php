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
     * @var string|null
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
        ?string $description,
        Meta $meta,
        int $sort
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
        $product->sort = $sort;
        return $product;
    }

    public function update(
        DateTimeImmutable $updatedDate,
        string $title,
        string $url,
        string $sku,
        Price $price,
        int $warehouse,
        float $weight,
        ?string $description,
        Meta $meta,
        int $sort,
        bool $isDeleted
    ): void
    {
        $this->updatedDate = $updatedDate;
        $this->title = $title;
        $this->url = $url;
        $this->sku = $sku;
        $this->price = $price;
        $this->warehouse = $warehouse;
        $this->weight = $weight;
        $this->description = $description;
        $this->meta = $meta;
        $this->sort = $sort;
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedDate(): DateTimeImmutable
    {
        return $this->createdDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedDate(): DateTimeImmutable
    {
        return $this->updatedDate;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getWarehouse(): int
    {
        return $this->warehouse;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @return Meta
     */
    public function getMeta(): Meta
    {
        return $this->meta;
    }


}