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

    /**
     * @param $imageOrders
     * @param $fileName
     * @param int $counter
     * @return false|int|string
     */
    private function getImageKey($imageOrders, $fileName, int $counter)
    {
        if ($imageOrders === null) {
            $key = $counter; // no sort order provided
        } else {
            // sort order provided for all or some images
            $key = array_search($fileName, $imageOrders, true);
            if ($key === false) {
                $key = uniqid('', false);
            }
        }

        return $key;
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
            $i = 0;
            $files = [];
            foreach ($finder as $file) {
                $fileName = $file->getRelativePathname();
                // set images key to simple counter value if no order provided or based on sort order
                $key = $this->getImageKey($imageOrders, $fileName, $i);
                $files[$key] = new ImageItem(
                    $fileName,
                    $file->getRealPath(),
                    str_replace($this->params->get('kernel.project_dir') . DIRECTORY_SEPARATOR . 'public', '', $file->getRealPath())
                );
                $i++;
            }
            ksort($files);
            return $files;
        }

        return [];
    }
}