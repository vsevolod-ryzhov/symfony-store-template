<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Photos\Upload;

use App\Domain\Product\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @Assert\File(
     *     maxSize = "2024k",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload an image"
     * )
     */
    public $file;

    /**
     * Command constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->id = $product->getId();
    }
}