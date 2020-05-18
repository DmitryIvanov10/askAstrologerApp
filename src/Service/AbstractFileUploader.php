<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\AppException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * TODO cover with unit test
 */
abstract class AbstractFileUploader
{
    private const FILE_NAME_TEMPLATE = '%s-%s.%s';

    private string $uploadsDirectory;

    public function __construct(string $uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    /**
     * @throws AppException
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );
        $fileName = sprintf(
            self::FILE_NAME_TEMPLATE,
            $safeFilename,
            uniqid(),
            $file->guessExtension()
        );

        try {
            $file->move($this->getUploadsDirectory(), $fileName);
        } catch (FileException $exception) {
            throw new AppException(
                sprintf(
                    'Cannot save file %s with extension %s to %s',
                    $safeFilename,
                    $file->guessExtension(),
                    $this->getUploadsDirectory()
                ), 0, $exception
            );
        }

        return $fileName;
    }

    public function getUploadsDirectory(): string
    {
        return sprintf(
            '%s/%s',
            $this->uploadsDirectory,
            $this->getFileTypeSubdirectory()
        );
    }

    abstract protected function getFileTypeSubdirectory(): string;
}
