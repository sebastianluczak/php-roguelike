<?php

namespace App\Model\Tile;

use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileLogic\BossRoomTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class BossRoomTile extends AbstractTile
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
        return new BossRoomTileLogic($scale);
    }

    public function draw(): string
    {
        return '<fg=bright-red>x</>';
    }
}
