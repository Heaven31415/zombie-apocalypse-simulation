<?php

namespace App\Service;

use App\Entity\Human;
use App\Entity\Resource;
use App\Entity\Zombie;
use App\Repository\HumanRepository;
use App\Repository\ResourceRepository;
use App\Repository\ZombieRepository;
use Doctrine\ORM\EntityManagerInterface;

class SimulationService
{
    /** @var Human[] */
    private readonly array $humans;
    /** @var Zombie[] */
    private readonly array $zombies;
    /** @var Resource[] */
    private readonly array $resources;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HumanRepository $humanRepository,
        private readonly ZombieRepository $zombieRepository,
        private readonly ResourceRepository $resourceRepository
    ) {
    }

    public function getState(): array
    {
        $this->humans = $this->humanRepository->findAll();
        $this->zombies = $this->zombieRepository->findAll();
        $this->resources = $this->resourceRepository->findAll();

        $state = [];

        for ($y = 1; $y <= 20; $y++) {
            $row = [];

            for ($x = 1; $x <= 20; $x++) {
                $row[] = $this->whatIsAt($x, $y);
            }

            $state[] = $row;
        }

        return $state;
    }

    public function updateState(): void
    {
        $humans = $this->humanRepository->findAll();
        $zombies = $this->zombieRepository->findAll();
        $resources = $this->resourceRepository->findAll();

        foreach ($humans as $human) {
            $human->tryToSurvive($zombies, $resources, $this->entityManager);
        }

        foreach ($zombies as $zombie) {
            $zombie->tryToInfect($humans, $this->entityManager);
        }

        $this->entityManager->flush();
    }

    private function whatIsAt(int $x, int $y): string
    {
        $foundHumans = 0;
        $foundZombies = 0;
        $foundResources = 0;
        $lastFoundResource = null;

        foreach ($this->humans as $human) {
            if ($human->getX() == $x && $human->getY() == $y) {
                ++$foundHumans;
            }
        }

        foreach ($this->zombies as $zombie) {
            if ($zombie->getX() == $x && $zombie->getY() == $y) {
                ++$foundZombies;
            }
        }

        foreach ($this->resources as $resource) {
            if ($resource->getX() == $x && $resource->getY() == $y) {
                ++$foundResources;

                $lastFoundResource = $resource;
            }
        }

        if ($foundHumans === 0 && $foundZombies === 0 && $foundResources === 0) {
            return '.'; // Empty
        }

        if ($foundHumans === 1 && $foundZombies === 0 && $foundResources === 0) {
            return 'H'; // Human
        }

        if ($foundHumans === 0 && $foundZombies === 1 && $foundResources === 0) {
            return 'Z';  // Zombie
        }

        if ($foundHumans === 0 && $foundZombies === 0 && $foundResources === 1) {
            return substr($lastFoundResource->getType(), 0, 1); // Ammo or Food
        }

        return 'M'; // Mixed
    }
}