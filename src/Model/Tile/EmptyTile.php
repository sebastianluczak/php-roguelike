<?php

namespace App\Model\Tile;

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

    public function handleLogic(int $mapLevel)
    {
        // TODO: Implement handleLogic() method.
    }

    public function draw(): string
    {
        return " ";
    }
}