<?php

namespace App\Model\Tile;

class ExitTile extends AbstractTile
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

    public function handleLogic(int $mapLevel)
    {
        return true;
    }

    public function draw(): string
    {
        return "<fg=bright-cyan>&</>";
    }
}