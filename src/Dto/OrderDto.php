<?php
declare(strict_types=1);

namespace App\Dto;

class OrderDto
{
    private int $astrologerId;
    private int $serviceId;
    private float $price;
    private string $customerEmail;
    private ?string $customerName;

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

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): self
    {
        $this->customerName = $customerName;
        return $this;
    }
}
