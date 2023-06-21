<?php

namespace App\Entity;

use App\Repository\HumanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HumanRepository::class)]
class Human extends Entity
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

    public function zombify(): Zombie
    {
        $zombie = new Zombie();

        $zombie->setName($this->getName())
            ->setX($this->getX())
            ->setY($this->getY());

        return $zombie;
    }

    public function tryToSurvive(
        array &$zombies,
        array &$resources,
        EntityManagerInterface $entityManager
    ): void {
        if (count($zombies) === 0 && count($resources) === 0) {
            return;
        }

        if (count($zombies) !== 0 && count($resources) === 0) {
            usort($zombies, function ($a, $b) {
                $distanceA = Entity::distance($a, $this);
                $distanceB = Entity::distance($b, $this);

                return $distanceA <=> $distanceB;
            });

            $zombie = $zombies[0]; // Nearest zombie
            $distanceToZombie = Entity::distance($this, $zombie);

            if ($distanceToZombie < 5.0 && $this->getAmmoCount() !== 0) {
                $this->shootZombie($zombie, $zombies, $entityManager);
            } else {
                $this->moveAwayFromZombie($zombie);
            }
        } elseif (count($zombies) === 0 && count($resources) !== 0) {
            usort($resources, function ($a, $b) {
                $distanceA = Entity::distance($a, $this);
                $distanceB = Entity::distance($b, $this);

                return $distanceA <=> $distanceB;
            });

            $resource = $resources[0]; // Nearest resource

            if ($this->isAtTheSamePositionAs($resource)) {
                $this->pickUpResource($resource, $resources, $entityManager);
            } else {
                $this->moveTowardsResource($resource);
            }
        } else {
            usort($zombies, function ($a, $b) {
                $distanceA = Entity::distance($a, $this);
                $distanceB = Entity::distance($b, $this);

                return $distanceA <=> $distanceB;
            });

            usort($resources, function ($a, $b) {
                $distanceA = Entity::distance($a, $this);
                $distanceB = Entity::distance($b, $this);

                return $distanceA <=> $distanceB;
            });

            $zombie = $zombies[0]; // Nearest zombie
            $resource = $resources[0]; // Nearest resource

            $distanceToZombie = Entity::distance($this, $zombie);
            $distanceToResource = Entity::distance($this, $resource);

            if ($distanceToZombie < 3.0 && $this->getAmmoCount() !== 0) {
                $this->shootZombie($zombie, $zombies, $entityManager);
            } elseif ($this->isAtTheSamePositionAs($resource)) {
                $this->pickUpResource($resource, $resources, $entityManager);
            } elseif ($distanceToZombie < $distanceToResource) {
                $this->moveAwayFromZombie($zombie);
            } else {
                $this->moveTowardsResource($resource);
            }
        }

        $entityManager->persist($this);
    }

    private function shootZombie(
        Zombie $zombie,
        array &$zombies,
        EntityManagerInterface $entityManager
    ): void {
        $entityManager->remove($zombie);
        array_shift($zombies);

        $this->setAmmoCount($this->getAmmoCount() - 1);
    }

    private function moveAwayFromZombie(Zombie $zombie): void
    {
        $this->moveAwayFrom($zombie);
        if ($this->getFoodCount() !== 0) {
            $this->moveAwayFrom($zombie);
            $this->setFoodCount($this->getFoodCount() - 1);
        }
    }

    private function pickUpResource(
        Resource $resource,
        array &$resources,
        EntityManagerInterface $entityManager
    ): void {
        switch ($resource->getType()) {
            case 'Ammo':
                $this->setAmmoCount($this->getAmmoCount() + $resource->getAmount());
                break;
            case 'Food':
                $this->setFoodCount($this->getFoodCount() + $resource->getAmount());
                break;
        }

        $entityManager->remove($resource);
        array_shift($resources);
    }

    private function moveTowardsResource(Resource $resource): void
    {
        $this->moveTowards($resource);
        if ($this->getFoodCount() !== 0) {
            $this->moveTowards($resource);
            $this->setFoodCount($this->getFoodCount() - 1);
        }
    }
}
