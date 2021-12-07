<?php

namespace App\Model\Tile\TileLogic\City;

use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\Tile\TileLogic\TileLogicInterface;

class PavementTileLogic implements TileLogicInterface
{
    protected string $rawMessage;
    protected string $messageClass;
    protected ?CreatureInterface $creature;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $this->rawMessage = "";
        $this->creature = null;
        $this->messageClass = MessageClassEnum::STANDARD();
    }

    public function process(PlayerInterface $player)
    {
    }

    public function hasAdventureLogMessage(): bool
    {
        return !empty($this->rawMessage);
    }

    public function getAdventureLogMessage(): string
    {
        return $this->rawMessage;
    }

    public function getAdventureLogMessageClass(): string
    {
        return $this->messageClass;
    }

    public function hasEncounter(): bool
    {
        if ($this->creature instanceof CreatureInterface) {
            return true;
        }

        return false;
    }

    public function getEncounteredCreature(): ?CreatureInterface
    {
        return $this->creature;
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
