<?php

declare(strict_types=1);


namespace App\Domain\Product\Service;


use App\Domain\Product\Entity\Product;
use DomainException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class Image
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params){
        $this->params = $params;
    }

    private function getImageOrders(?string $imageOrders)
    {
        return (!empty($imageOrders)) ? json_decode(trim(stripslashes($imageOrders), '"'), true) : null;
    }

    /**
     * Get images for admin section where we get product entity
     * @param Product $product
     * @return array
     */
    public function getProductImagesByEntity(Product $product): array
    {
        return $this->getProductImages((int)$product->getId(), $product->getImageOrder());
    }

    public function getProductImages(int $productId, ?string $imageOrders): array
    {
        $parsedImageOrders = $this->getImageOrders($imageOrders);

        $dirName = $this->params->get('products_directory') . DIRECTORY_SEPARATOR . $productId;
        if (!is_dir($dirName) && !mkdir($dirName) && !is_dir($dirName)) {
            throw new DomainException(sprintf('Directory "%s" was not created', $dirName));
        }
        $finder = new Finder();
        $finder->files()->in($dirName);

        if ($finder->hasResults()) {
            $files = [];
            $unsortedFiles = [];
            foreach ($finder as $file) {
                $fileName = $file->getRelativePathname();

                $imageObject = new ImageItem(
                    $fileName,
                    $file->getRealPath(),
                    str_replace($this->params->get('kernel.project_dir') . DIRECTORY_SEPARATOR . 'public', '', $file->getRealPath())
                );

                if ($parsedImageOrders && in_array($fileName, $parsedImageOrders, true)) {
                    // if sort order provided for file
                    $files[array_search($fileName, $parsedImageOrders, true)] = $imageObject;
                } else {
                    $unsortedFiles[] = $imageObject;
                }
            }
            ksort($files);

            // add all files without provided sort order
            foreach ($unsortedFiles as $unsortedFile) {
                $files[] = $unsortedFile;
            }
            return $files;
        }

        return [];
    }

}