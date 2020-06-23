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

    public function getProductPhotos(Product $product): array
    {
        $dirName = $this->params->get('products_directory') . DIRECTORY_SEPARATOR . $product->getId();
        if (!is_dir($dirName) && !mkdir($dirName) && !is_dir($dirName)) {
            throw new DomainException(sprintf('Directory "%s" was not created', $dirName));
        }
        $finder = new Finder();
        $finder->files()->in($dirName);
        if ($finder->hasResults()) {
            $files = [];
            foreach ($finder as $file) {
                $files[] = [
                    'fileName' => $file->getRelativePathname(),
                    'rel' => str_replace($this->params->get('kernel.project_dir') . DIRECTORY_SEPARATOR . 'public', '', $file->getRealPath())
                ];
            }
            return $files;
        }

        return [];
    }
}