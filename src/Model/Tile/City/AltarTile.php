<?php

namespace App\Model\Tile\City;

use App\Model\Player\PlayerInterface;
use App\Model\Stats\StatsInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\TileInteraction\AltarTileInteraction;
use App\Model\Tile\TileInteraction\TileInteractionInterface;
use App\Model\Tile\TileLogic\NoLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;
use App\Traits\Tile\PermanentTileTrait;

class AltarTile extends AbstractTile
{
    use PermanentTileTrait;

    public function isInteractable(): bool
    {
        return true;
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
        return false;
    }

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new NoLogic();
    }

    public function handleInteraction(PlayerInterface $player): TileInteractionInterface
    {
        return new AltarTileInteraction();
    }

    public function draw(): string
    {
        return '<fg=red>¥</>';
    }
}
