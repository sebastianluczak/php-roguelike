<?php

namespace App\Model\Loot;

class SkillBoost
{
    protected int $armorAmount;
    protected int $damageAmount;
    protected int $healthAmount;
    protected int $experience;

    public function __construct()
    {
        $this->armorAmount = random_int(1, 5);
        $this->damageAmount = random_int(1, 5);
        $this->healthAmount = random_int(1, 20);
        $this->experience = random_int(10, 100);
    }

    public function getArmorAmount(): int
    {
        return $this->armorAmount;
    }

    public function getDamageAmount(): int
    {
        return $this->damageAmount;
    }

    public function getHealthAmount(): int
    {
        return $this->healthAmount;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }
}