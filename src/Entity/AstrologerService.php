<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="astrologer_service",
 *     uniqueConstraints={
 *         @UniqueConstraint(name="astrologer_service", columns={"astrologer_id", "service_id"})
 *     }
 * )
 */
class AstrologerService
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Astrologer", inversedBy="astrologerServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private Astrologer $astrologer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="astrologerServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private Service $service;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\PositiveOrZero(message="Price cannot be negative.")
     */
    private float $price;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private bool $active;

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
}
