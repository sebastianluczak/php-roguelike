<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Imp extends AbstractCreature
{
    public function __construct(int $scale)
    {
        parent::__construct();

        $this->scale = $scale;
        $this->name = "Imp - " . $this->getRawName();
        $this->damage = $this->createRandomNumberInRangeWithScale(1, 1, $scale);
        $this->armor = $this->createRandomNumberInRangeWithScale(0, 1, $scale);
        $this->health = $this->createRandomNumberInRangeWithScale(3, 5, $scale);
        $this->experience = $this->createRandomNumberInRangeWithScale(5, 7, $scale);
    }

    public function handleLoot()
    {
        return new Gold($this->scale);
    }
}