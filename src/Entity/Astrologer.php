<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\AstrologerRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AstrologerRepository::class)
 * @ORM\Table(indexes={@Index(name="email", columns={"email"})})
 */
class Astrologer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Name cannot be blank.")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private ?string $surname;

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
     * @ORM\Column(type="string", name="image_path", length=255, nullable=true, unique=true)
     */
    private ?string $imagePath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AstrologerService", mappedBy="astrologer", fetch="EXTRA_LAZY")
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

    public function getId(): int
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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * @return Collection|AstrologerService[]
     */
    public function getAstrologerServices(): Collection
    {
        return $this->astrologerServices;
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
}
