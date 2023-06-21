<?php

namespace App\Entity;

abstract class Entity
{
    abstract public function getX(): ?int;

    abstract public function setX(int $x): static;

    abstract public function getY(): ?int;

    abstract public function setY(int $y): static;

    public function moveRandomly(): void
    {
        switch (rand(1, 4)) {
            case 1: // Move left
                if ($this->getX() > 1) {
                    $this->setX($this->getX() - 1);
                }
                break;
            case 2: // Move right
                if ($this->getX() < 20) {
                    $this->setX($this->getX() + 1);
                }
                break;
            case 3: // Move up
                if ($this->getY() > 1) {
                    $this->setY($this->getY() - 1);
                }
                break;
            case 4: // Move down
                if ($this->getY() < 20) {
                    $this->setY($this->getY() + 1);
                }
                break;
        }
    }

    public function moveTowards(Entity $target): void
    {
        if ($target->getX() < $this->getX()) { // Move left
            $this->setX($this->getX() - 1);
        } elseif ($target->getX() > $this->getX()) { // Move right
            $this->setX($this->getX() + 1);
        } elseif ($target->getY() < $this->getY()) { // Move up
            $this->setY($this->getY() - 1);
        } elseif ($target->getY() > $this->getY()) { // Move down
            $this->setY($this->getY() + 1);
        }
    }

    public function moveAwayFrom(Entity $target): void
    {
        if ($target->getX() < $this->getX()) { // Move right
            if ($this->getX() < 20) {
                $this->setX($this->getX() + 1);
            }
        } elseif ($target->getX() > $this->getX()) { // Move left
            if ($this->getX() > 1) {
                $this->setX($this->getX() - 1);
            }
        } elseif ($target->getY() < $this->getY()) { // Move down
            if ($this->getY() < 20) {
                $this->setY($this->getY() + 1);
            }
        } elseif ($target->getY() > $this->getY()) { // Move up
            if ($this->getY() > 1) {
                $this->setY($this->getY() - 1);
            }
        }
    }

    public function isAtTheSamePositionAs(Entity $target): bool
    {
        return $this->getX() === $target->getX() && $this->getY() === $target->getY();
    }

    public static function distance(Entity $a, Entity $b): float
    {
        return sqrt(pow($a->getX() - $b->getX(), 2) + pow($a->getY() - $b->getY(), 2));
    }
}