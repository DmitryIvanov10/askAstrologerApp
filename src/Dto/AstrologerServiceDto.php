<?php
declare(strict_types=1);

namespace App\Dto;

/**
 * @codeCoverageIgnore
 */
class AstrologerServiceDto
{
    private int $astrologerId;
    private int $serviceId;
    private float $price;

    public function getAstrologerId(): int
    {
        return $this->astrologerId;
    }

    public function setAstrologerId(int $astrologerId): self
    {
        $this->astrologerId = $astrologerId;
        return $this;
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}
