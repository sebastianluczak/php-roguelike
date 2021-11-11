<?php

namespace App\Model\Tile;

use App\Model\Tile\TileLogic\NoLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class EmptyTile extends AbstractTile
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

    public function handleLogic(int $mapLevel): TileLogicInterface
    {
        return new NoLogic();
    }

    public function draw(): string
    {
        return " ";
    }
}