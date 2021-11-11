<?php

namespace App\Model\Tile;

use App\Model\Creature\Imp;
use App\Model\Loot\SkillBoost;
use App\Model\Tile\TileLogic\ShopTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

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

    public function handleLogic(int $mapLevel): TileLogicInterface
    {
        return new ShopTileLogic($mapLevel);
    }

    public function draw(): string
    {
        return "<fg=yellow>$</>";
    }
}