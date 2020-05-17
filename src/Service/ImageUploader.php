<?php
declare(strict_types=1);

namespace App\Service;

class ImageUploader extends AbstractFileUploader
{
    private const SUBDIRECTORY_NAME = 'images';

    protected function getFileTypeSubdirectory(): string
    {
        return self::SUBDIRECTORY_NAME;
    }
}
