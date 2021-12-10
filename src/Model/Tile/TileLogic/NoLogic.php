<?php

declare(strict_types=1);

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;

class NoLogic implements TileLogicInterface
{
    protected RandomEventInterface $event;

    public function process(PlayerInterface $player): void
    {
    }

    public function hasAdventureLogMessage(): bool
    {
        return false;
    }

    public function getAdventureLogMessage(): string
    {
        return '';
    }

    public function getAdventureLogMessageClass(): MessageClassEnum
    {
        return MessageClassEnum::STANDARD();
    }

    public function hasEncounter(): bool
    {
        return false;
    }

    public function getEncounteredCreature(): ?CreatureInterface
    {
        return null;
    }

    public function getEvent(): RandomEventInterface
    {
        return $this->event;
    }

    public function hasEvent(): bool
    {
        return !empty($this->event);
    }
}
