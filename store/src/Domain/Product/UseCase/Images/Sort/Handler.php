<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Images\Sort;


use App\Domain\Product\Entity\Product;
use App\Domain\Product\Service\Image;
use App\Domain\Product\Service\ImageItem;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Image
     */
    private $image;

    public function __construct(EntityManagerInterface $em, Image $image)
    {
        $this->em = $em;
        $this->image = $image;
    }

    public function handle(Product $product, string $order): void
    {
        $images = $this->image->getProductImagesByEntity($product);
        $file_names = [];
        foreach ($images as $image) {
            /* @var $image ImageItem */
            $file_names[] = $image->getFileName();
        }

        $order = json_decode($order, true);
        foreach ($order as $key => $item) {
            if (!in_array($item, $file_names, true)) {
                unset($order[$key]);
            }
        }

        $product->setImageOrder(json_encode($order));
        $this->em->flush();
    }
}