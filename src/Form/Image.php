<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image
{
    private UploadedFile $file;

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }
}
