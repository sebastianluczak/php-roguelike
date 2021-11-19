<?php

namespace App\Model\Tile\City;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\TileLogic\City\PavementTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class PavementTile extends AbstractTile
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
        return new PavementTileLogic($scale);
    }

    public function draw(): string
    {
        return "<fg=gray>.</>";
    }
}