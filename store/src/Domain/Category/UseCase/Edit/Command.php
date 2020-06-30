<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Edit;

use App\Domain\Category\Entity\Category;
use DomainException;
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
     */
    public $parent;

    /**
     * Command constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        if ($category->getParent() === null) {
            throw new DomainException('Root category couldn\'t be changed');
        }

        $this->id = $category->getId();
        $this->name = $category->getName();
        $this->parent = $category->getParent()->getId();
    }
}