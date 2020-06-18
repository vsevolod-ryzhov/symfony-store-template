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
    private $title;

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

    public function __construct(?string $title = null, ?string $keywords = null, ?string $description = null)
    {
        $this->title = $title;
        $this->keywords = $keywords;
        $this->description = $description;
    }

    public function __toString(): string
    {
        /// TODO: create twig extension for pretty output
        return "$this->title\n$this->keywords\n$this->description";
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
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