<?php

namespace App\Model\Tile;

use App\Model\Creature\Imp;
use App\Model\Loot\SkillBoost;

class ShopTile extends AbstractTile
{
    public function isInteractable(): bool
    {
        return true;
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

    public function handleLogic(int $mapLevel)
    {
        return new SkillBoost();
    }

    public function draw(): string
    {
        return "<fg=yellow>$</>";
    }
}