<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderStatusRepository::class)
 * @ORM\Table(name="order_status", indexes={@Index(name="name", columns={"name"})})
 */
class OrderStatus
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     * @Assert\NotBlank(message="Name cannot be blank.")
     */
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
