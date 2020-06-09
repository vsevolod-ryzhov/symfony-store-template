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
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_date;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_date;

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
     * @var string
     * @ORM\Column(type="decimal", precision=7, scale=2, name="price")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=7, scale=2, name="old_price")
     */
    private $oldPrice;

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
     * @ORM\Column(type="string", name="description", nullable=true)
     */
    private $description;

    /**
     * @var bool
     * @ORM\Column(type="string", name="is_deleted")
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
}