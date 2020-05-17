<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(
 *     indexes={
 *         @Index(name="astrologer_service", columns={"astrologer_id", "service_id"}),
 *         @Index(name="status", columns={"status_id"}),
 *         @Index(name="customer_email", columns={"customer_email"})
 *     }
 * )
 */
class Order implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus")
     * @ORM\JoinColumn(name="status_id", nullable=false)
     */
    private OrderStatus $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Astrologer", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private Astrologer $astrologer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private Service $service;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\PositiveOrZero(message="Price cannot be negative.")
     */
    private float $price;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Email cannot be blank.")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private string $customerEmail;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $customerName;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): self
    {
        $this->status = $status;
        return $this;
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

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'astrologerId' => $this->getAstrologer()->getId(),
            'serviceId' => $this->getService()->getId(),
            'price' => $this->getPrice(),
            'customerEmail' => $this->getCustomerEmail(),
            'customerName' => $this->getCustomerName(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}