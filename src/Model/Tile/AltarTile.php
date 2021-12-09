<?php

namespace App\Model\Tile;

use App\Model\Creature\Imp;
use App\Model\Loot\SkillBoost;
use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileLogic\AltarTileLogic;
use App\Model\Tile\TileLogic\ShopTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class AltarTile extends AbstractTile
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

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new AltarTileLogic();
    }

    public function draw(): string
    {
        return "<fg=gray>o</>";
    }
}
