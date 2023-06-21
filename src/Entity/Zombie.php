<?php

namespace App\Entity;

use App\Repository\ZombieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ZombieRepository::class)]
class Zombie extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 20)]
    private ?int $x = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 20)]
    private ?int $y = null;

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

    /**
     * @param Human[]                $humans
     * @param EntityManagerInterface $entityManager
     *
     * @return void
     */
    public function tryToInfect(array &$humans, EntityManagerInterface $entityManager): void
    {
        if (count($humans) === 0) {
            $this->moveRandomly();

            return;
        }

        usort($humans, function ($a, $b) {
            $distanceA = Entity::distance($a, $this);
            $distanceB = Entity::distance($b, $this);

            return $distanceA <=> $distanceB;
        });

        $human = $humans[0]; // Nearest human

        if ($this->isAtTheSamePositionAs($human)) {
            $entityManager->persist($human->zombify());
            $entityManager->remove($human);
            array_shift($humans);
        } else {
            $this->moveTowards($human);
        }

        $entityManager->persist($this);
    }
}
