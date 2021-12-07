<?php

namespace App\Model\Tile\City;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\TileLogic\NoLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class AltarTile extends AbstractTile
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
        return false;
    }

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new NoLogic();
    }

    public function draw(): string
    {
        return "<fg=bright-white>O</>";
    }
}
