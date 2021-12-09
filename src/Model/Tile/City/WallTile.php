<?php

namespace App\Model\Tile\City;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\TileLogic\NoLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class WallTile extends AbstractTile
{
    public function isInteractable(): bool
    {
        return false;
    }

    public function isPassable(): bool
    {
        return false;
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
        return new NoLogic($scale);
    }

    public function draw(): string
    {
        return '<fg=bright-white>#</>';
    }
}
