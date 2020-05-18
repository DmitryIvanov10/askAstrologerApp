<?php
declare(strict_types=1);

namespace App\Service;

/**
 * @codeCoverageIgnore
 */
class ImageUploader extends AbstractFileUploader
{
    private const SUBDIRECTORY_NAME = 'images';

    /**
     * @inheritDoc
     */
    protected function getFileTypeSubdirectory(): string
    {
        return self::SUBDIRECTORY_NAME;
    }
}
