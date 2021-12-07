<?php

namespace App\Model\Tile;

use App\Model\Player\PlayerInterface;
use App\Model\Tile\TileInteraction\EmptyTileInteraction;
use App\Model\Tile\TileInteraction\TileInteractionInterface;

abstract class AbstractTile implements TileInterface
{
    protected bool $isPassable;
    protected bool $isInteractable;
    protected bool $isSpawn;
    protected bool $hasLogic;

    public function handleInteraction(PlayerInterface $player): TileInteractionInterface
    {
        return new EmptyTileInteraction();
    }
}
