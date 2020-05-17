<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\AppException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractFileUploader
{
    private const FILE_NAME_TEMPLATE = '%s-%s.%s';

    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
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
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $exception) {
            throw new AppException(
                sprintf(
                    'Cannot save file %s with extension %s to %s',
                    $safeFilename,
                    $file->guessExtension(),
                    $this->getTargetDirectory()
                ), 0, $exception
            );
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return sprintf(
            '%s/%s',
            $this->targetDirectory,
            $this->getFileTypeSubdirectory()
        );
    }

    abstract protected function getFileTypeSubdirectory(): string;
}
