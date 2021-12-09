<?php

namespace App\Model\Tile;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileLogic\CorridorTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

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

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new CorridorTileLogic($scale);
    }

    public function draw(): string
    {
        return '<fg=gray>.</>';
    }
}
