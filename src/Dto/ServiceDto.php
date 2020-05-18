<?php
declare(strict_types=1);

namespace App\Dto;

/**
 * @codeCoverageIgnore
 */
class ServiceDto
{
    private string $name;
    private ?string $description = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
