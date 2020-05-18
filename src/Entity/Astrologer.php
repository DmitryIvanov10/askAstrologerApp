<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\AstrologerRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AstrologerRepository::class)
 * @ORM\Table(indexes={@Index(name="email", columns={"email"})})
 * @UniqueEntity(fields={"email"}, message="An astrologer with this email already exists.")
 * @UniqueEntity("imageFilename")
 * @codeCoverageIgnore
 */
class Astrologer implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Name cannot be blank.")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private ?string $surname = null;

    /**
     * @ORM\Column(type="date", name="date_of_birth")
     * @Assert\NotBlank(message="Date of birth cannot be blank.")
     * @Assert\Date
     */
    private DateTimeInterface $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Email cannot be blank.")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private string $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description cannot be blank.")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", name="image_filename", length=255, nullable=true, unique=true)
     */
    private ?string $imageFilename = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AstrologerService", mappedBy="astrologer", fetch="LAZY")
     */
    private Collection $astrologerServices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="astrologer")
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getDateOfBirth(): ?DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;
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
            $astrologerService->setAstrologer($this);
        }

        return $this;
    }

    public function removeAstrologerService(AstrologerService $astrologerService): self
    {
        if ($this->astrologerServices->contains($astrologerService)) {
            $this->astrologerServices->removeElement($astrologerService);
            // set the owning side to null (unless already changed)
            if ($astrologerService->getAstrologer() === $this) {
                $astrologerService->setAstrologer(null);
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
            $order->setAstrologer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getAstrologer() === $this) {
                $order->setAstrologer(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'dateOfBirth' => $this->getDateOfBirth()->format('d.m.Y'),
            'email' => $this->getEmail(),
            'personalInfo' => $this->getDescription(),
            'imageFilename' => $this->getImageFilename()
        ];
    }
}
