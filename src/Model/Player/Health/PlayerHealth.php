<?php

namespace App\Model\Player\Health;

class PlayerHealth implements PlayerHealthInterface
{
    protected int $health;
    protected int $maxHealth;

    public function __construct(int $playerHealth)
    {
        $this->health = $playerHealth;
        $this->maxHealth = $playerHealth;
    }

    public function modifyHealth(int $amount): PlayerHealthInterface
    {
        if ($amount > 0) {
            if ($amount + $this->health >= $this->maxHealth) {
                $this->health = $this->maxHealth; // heal completely
            } else {
                $this->health = $this->health + $amount;
            }
        } else if ($amount < 0) {
            if ($this->health - $amount <= 0) {
                $this->health = 0; // player death
            } else {
                $this->health = $this->health + $amount;
            }
        }

        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }
}