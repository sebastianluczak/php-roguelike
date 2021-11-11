<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Golem extends AbstractCreature
{
    public function __construct(int $scale)
    {
        parent::__construct();

        $this->scale = $scale;

        $this->name = "Golem - " . $this->getRawName();
        $this->damage = $this->createRandomNumberInRangeWithScale(2, 3, $scale);
        $this->armor = $this->createRandomNumberInRangeWithScale(15, 20, $scale);
        $this->health = $this->createRandomNumberInRangeWithScale(15, 25, $scale);
        $this->experience = $this->createRandomNumberInRangeWithScale(20, 50, $scale);
    }

    public function handleLoot()
    {
        return new Gold(1.5 * $this->scale);
    }
}