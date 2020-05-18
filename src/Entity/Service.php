<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(indexes={@Index(name="name", columns={"name"})})
 * @UniqueEntity(fields={"name"}, message="A service with this name already exists.")
 */
class Service implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Name cannot be blank.")
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AstrologerService", mappedBy="service", fetch="LAZY")
     */
    private Collection $astrologerServices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="service")
     */
    private Collection $orders;

    public function __construct()
    {
        $this->astrologerServices = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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

    /**
     * @return Collection|AstrologerService[]
     */
    public function getAstrologerServices(): Collection
    {
        return $this->astrologerServices;
    }

    public function addAstrologerService(AstrologerService $astrologerService): self
    {
        if (!$this->astrologerServices->contains($astrologerService)) {
            $this->astrologerServices[] = $astrologerService;
            $astrologerService->setService($this);
        }

        return $this;
    }

    public function removeAstrologerService(AstrologerService $astrologerService): self
    {
        if ($this->astrologerServices->contains($astrologerService)) {
            $this->astrologerServices->removeElement($astrologerService);
            // set the owning side to null (unless already changed)
            if ($astrologerService->getService() === $this) {
                $astrologerService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setService($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getService() === $this) {
                $order->setService(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription()
        ];
    }
}
