<?php

namespace App\Model\Tile;

use App\Model\Creature\Dragon;
use App\Model\Creature\Golem;
use App\Model\Creature\Imp;
use App\Model\Npc\City\Shopkeeper;
use App\Model\Player\PlayerInterface;
use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileInteraction\NpcTileInteraction;
use App\Model\Tile\TileInteraction\TileInteractionInterface;
use App\Model\Tile\TileLogic\CorridorTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class CorridorTile extends AbstractTile
{
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
        return true;
    }

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new CorridorTileLogic($scale);
    }

    public function handleInteraction(PlayerInterface $player): TileInteractionInterface
    {
        $npc = new Shopkeeper();

        return new NpcTileInteraction($player, $npc);
    }

    public function draw(): string
    {
        return "<fg=gray>.</>";
    }
}
