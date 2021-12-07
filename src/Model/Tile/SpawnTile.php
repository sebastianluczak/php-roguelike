<?php

namespace App\Model\Tile;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileLogic\TileLogicInterface;
use App\Model\Tile\TileLogic\NoLogic;

class SpawnTile extends AbstractTile
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
        return true;
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
        return "<options=bold,blink;fg=bright-red>@</>";
    }
}
