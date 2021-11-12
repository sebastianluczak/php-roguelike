<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Goblin extends AbstractCreature
{
    public function __construct(int $scale)
    {
        parent::__construct();

        $this->scale = $scale;
        $this->name = "Goblin - " . $this->getRawName();
        $this->damage = $this->createRandomNumberInRangeWithScale(1, 3, $scale);
        $this->armor = $this->createRandomNumberInRangeWithScale(1, 2, $scale);
        $this->health = $this->createRandomNumberInRangeWithScale(4, 6, $scale);
        $this->experience = $this->createRandomNumberInRangeWithScale(6, 8, $scale);
    }

    public function handleLoot()
    {
        return new Gold($this->scale);
    }
}