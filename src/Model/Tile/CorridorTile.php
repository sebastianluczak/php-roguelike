<?php

namespace App\Model\Tile;

use App\Model\Creature\Dragon;
use App\Model\Creature\Golem;
use App\Model\Creature\Imp;

class CorridorTile extends AbstractTile
{
    public function isInteractable(): bool
    {
        return false;
    }

    public function isPassable(): bool
    {
        return true;
    }

    public function isSpawn(): bool
    {
        return false;
    }

    public function hasLogic(): bool
    {
        return true;
    }

    public function handleLogic()
    {
        $roll = random_int(0, 100);
        if ($roll <= 1) {
            return new Dragon();
        } else if ($roll <= 5) {
            return new Golem();
        } else if ($roll <= 100) {
            return new Imp();
        }

        return null;
    }

    public function draw(): string
    {
        return ".";
    }
}