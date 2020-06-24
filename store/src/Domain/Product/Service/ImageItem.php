<?php

declare(strict_types=1);


namespace App\Domain\Product\Service;


class ImageItem
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $absPath;

    /**
     * @var string
     */
    private $relPath;

    public function __construct(string $fileName, string $absPath, string $relPath)
    {
        $this->fileName = $fileName;
        $this->absPath = $absPath;
        $this->relPath = $relPath;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getAbsPath(): string
    {
        return $this->absPath;
    }

    /**
     * @return string
     */
    public function getRelPath(): string
    {
        return $this->relPath;
    }
}