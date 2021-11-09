<?php

namespace App\Model\Tile;

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

    public function handleLogic()
    {
        // TODO: Implement handleLogic() method.
    }

    public function draw(): string
    {
        return "<options=bold;fg=red>@</>";
    }
}