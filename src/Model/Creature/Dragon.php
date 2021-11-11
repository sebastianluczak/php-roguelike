<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Dragon extends AbstractCreature
{
    public function __construct(float $scale)
    {
        parent::__construct();

        $this->scale = $scale;
        $this->name = "Dragon - " . $this->getRawName();
        $this->damage = $this->createRandomNumberInRangeWithScale(15, 20, $scale);
        $this->armor = $this->createRandomNumberInRangeWithScale(45, 70, $scale);
        $this->health = $this->createRandomNumberInRangeWithScale(30, 50, $scale);
        $this->experience = $this->createRandomNumberInRangeWithScale(50, 70, $scale);
    }

    public function handleLoot()
    {
        return new Gold(3 * $this->scale);
    }
}