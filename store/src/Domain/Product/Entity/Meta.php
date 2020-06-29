<?php

declare(strict_types=1);


namespace App\Domain\Product\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Meta
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct(?string $name = null, ?string $keywords = null, ?string $description = null)
    {
        $this->name = $name;
        $this->keywords = $keywords;
        $this->description = $description;
    }

    public function __toString(): string
    {
        /// TODO: create twig extension for pretty output
        return "$this->name\n$this->keywords\n$this->description";
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}