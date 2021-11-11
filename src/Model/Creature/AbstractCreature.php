<?php

namespace App\Model\Creature;

abstract class AbstractCreature implements CreatureInterface
{
    protected int $health;
    protected int $damage;
    protected int $armor;
    protected string $name;
    protected int $experience;
    
    public function getExperience(): int
    {
        return $this->experience;
    }
}