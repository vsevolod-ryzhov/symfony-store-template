<?php

declare(strict_types=1);


namespace App\Domain\Category\Entity;

use App\Domain\Product\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index as Index;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(
 *     name="product_categories",
 *     indexes={
 *          @Index(name="lft_ix", columns={"lft"}),
 *          @Index(name="rgt_ix", columns={"rgt"}),
 *          @Index(name="lvl_ix", columns={"lvl"})
 *     })
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @Gedmo\TreeLeft()
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel()
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight()
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot()
     * @ORM\ManyToOne(targetEntity="App\Domain\Category\Entity\Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="cascade")
     */
    private $root;

    /**
     * @Gedmo\TreeParent()
     * @ORM\ManyToOne(targetEntity="App\Domain\Category\Entity\Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Category\Entity\Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Product\Entity\Product", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public static function create(string $name): self
    {
        $category = new self();
        $category->name = $name;
        return $category;
    }

    /**
     * @return int|null
     */
    public function getLft(): ?int
    {
        return $this->lft;
    }

    /**
     * @return int|null
     */
    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    /**
     * @return null|Product[]|ArrayCollection|Collection
     */
    public function getProducts(): ?Collection
    {
        return $this->products;
    }

    /**
     * @return null|integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|Category
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return null|Category
     */
    public function getRoot(): Category
    {
        return $this->root;
    }

    /**
     * @return null|Category[]|ArrayCollection|Collection
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @return null|int
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
    }
}