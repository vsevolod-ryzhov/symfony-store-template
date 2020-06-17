<?php

declare(strict_types=1);


namespace App\Domain\Product\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Price
{
    /**
     * @var float
     * @ORM\Column(type="decimal", precision=7, scale=2, name="price")
     */
    private $price;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=7, scale=2, name="old_price")
     */
    private $oldPrice;

    public function __construct(float $price, ?float $oldPrice)
    {
        $this->price = $price;
        $this->oldPrice = $oldPrice ?: 0;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->price;
    }

    /**
     * @return float
     */
    public function getOldPrice(): float
    {
        return (float)$this->oldPrice;
    }
}