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
     * @var ?string
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @var ?string
     * @ORM\Column(type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var ?string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct(?string $title, ?string $keywords, ?string $description)
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
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return ?string
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}