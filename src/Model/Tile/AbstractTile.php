<?php

namespace App\Model\Tile;

abstract class AbstractTile implements TileInterface
{
    protected bool $isPassable;
    protected bool $isInteractable;
    protected bool $isSpawn;
    protected bool $hasLogic;
}