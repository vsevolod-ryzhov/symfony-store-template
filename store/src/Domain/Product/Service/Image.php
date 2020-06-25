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

    private function getImageOrders(Product $product): ?array
    {
        $imageOrders = null;
        // get existing sort order if provided
        if (!empty($product->getImageOrder())) {
            $imageOrders = json_decode($product->getImageOrder(), true);
        }

        return $imageOrders;
    }

    public function getProductImages(Product $product): array
    {
        $imageOrders = $this->getImageOrders($product);

        $dirName = $this->params->get('products_directory') . DIRECTORY_SEPARATOR . $product->getId();
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

                if ($imageOrders && in_array($fileName, $imageOrders, true)) {
                    // if sort order provided for file
                    $files[array_search($fileName, $imageOrders, true)] = $imageObject;
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