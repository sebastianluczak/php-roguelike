<?php

namespace App\Model\Tile\TileLogic;

use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;

interface TileLogicInterface
{
    public function process(PlayerInterface $player);
    public function hasAdventureLogMessage(): bool;
    public function getAdventureLogMessage(): string;
    public function getAdventureLogMessageClass(): string;
    public function hasEncounter(): bool;
    public function getEncounteredCreature(): ?CreatureInterface;
    public function getEvent(): RandomEventInterface;
    public function hasEvent(): bool;
}