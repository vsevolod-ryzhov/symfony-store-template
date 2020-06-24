<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Images\Delete;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Handler
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function handle(Command $command): void
    {
        $filePath = $this->params->get('products_directory') . DIRECTORY_SEPARATOR . $command->id . DIRECTORY_SEPARATOR . $command->file;
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}