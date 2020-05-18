<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="astrologer_service",
 *     uniqueConstraints={
 *         @UniqueConstraint(name="astrologer_service", columns={"astrologer_id", "service_id"})
 *     }
 * )
 * @UniqueEntity(fields={"astrologer","service"}, message="An astrologer already has this service.")
 */
class AstrologerService implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Astrologer", inversedBy="astrologerServices", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private Astrologer $astrologer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="astrologerServices", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private Service $service;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\Positive(message="Price cannot be negative or zero.")
     */
    private float $price;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private bool $active = true;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAstrologer(): Astrologer
    {
        return $this->astrologer;
    }

    public function setAstrologer(Astrologer $astrologer): self
    {
        $this->astrologer = $astrologer;
        return $this;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'service' => $this->getService(),
            'price' => $this->getPrice()
        ];
    }
}
