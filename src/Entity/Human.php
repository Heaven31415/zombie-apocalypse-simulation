<?php

namespace App\Entity;

use App\Repository\HumanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HumanRepository::class)]
class Human
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $x = null;

    #[ORM\Column]
    private ?int $y = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $ammoCount = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $foodCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function getAmmoCount(): ?int
    {
        return $this->ammoCount;
    }

    public function setAmmoCount(int $ammoCount): static
    {
        $this->ammoCount = $ammoCount;

        return $this;
    }

    public function getFoodCount(): ?int
    {
        return $this->foodCount;
    }

    public function setFoodCount(int $foodCount): static
    {
        $this->foodCount = $foodCount;

        return $this;
    }
}
