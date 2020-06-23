<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Photos\Upload;


use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class Handler
{
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(SluggerInterface $slugger, LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
        $this->params = $params;
    }

    public function handle(Command $command): void
    {
        if ($command->files) {
            foreach ($command->files as $file) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($fileName);
                $newFilename = $safeFilename . '-' . uniqid('', false) . '.' . $file->guessExtension();

                $dirName = $this->params->get('products_directory') . DIRECTORY_SEPARATOR . $command->id;
                if (!is_dir($dirName) && !mkdir($dirName) && !is_dir($dirName)) {
                    throw new DomainException(sprintf('Directory "%s" was not created', $dirName));
                }
                try {
                    $file->move(
                        $dirName,
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }
    }
}