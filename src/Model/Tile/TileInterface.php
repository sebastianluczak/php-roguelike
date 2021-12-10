<?php

namespace App\Model\Tile;

use App\Model\Player\PlayerInterface;
use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileInteraction\TileInteractionInterface;
use App\Model\Tile\TileLogic\TileLogicInterface;

interface TileInterface
{
    public function isPassable(): bool;

    public function isInteractable(): bool;

    public function isSpawn(): bool;

    public function draw(): string;

    public function hasLogic(): bool;

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface;

    public function handleInteraction(PlayerInterface $player): TileInteractionInterface;
}
