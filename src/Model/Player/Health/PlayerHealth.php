<?php

namespace App\Model\Player\Health;

use App\Enum\Player\Health\HealthActionEnum;

class PlayerHealth implements PlayerHealthInterface
{
    protected int $health;
    protected int $maxHealth;

    public function __construct(int $playerHealth = 100)
    {
        $this->health = $playerHealth;
        $this->maxHealth = $playerHealth;
    }

    public function modifyHealth(int $amount, HealthActionEnum $action): PlayerHealthInterface
    {
        switch ($action) {
            case HealthActionEnum::INCREASE():
                if ($amount + $this->health >= $this->maxHealth) {
                    $this->health = $this->maxHealth; // heal completely
                } else {
                    $this->health = $this->health + $amount;
                }
                break;
            case HealthActionEnum::DECREASE():
                if ($this->health - $amount <= 0) {
                    $this->health = 0; // player death
                } else {
                    $this->health = $this->health - $amount;
                }
                break;
        }

        return $this;
    }

    public function increaseMaxHealth(int $amount): PlayerHealthInterface
    {
        $this->maxHealth = $this->maxHealth + $amount;

        return $this;
    }

    public function getWarningThreshold(): int
    {
        return ceil($this->getMaxHealth() / 4);
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
