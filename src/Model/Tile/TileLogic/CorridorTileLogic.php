<?php

namespace App\Model\Tile\TileLogic;

use App\Model\Creature\CreatureInterface;
use App\Model\Creature\Dragon;
use App\Model\Creature\Golem;
use App\Model\Creature\Imp;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\RandomEvent\ThiefArrivedGameEvent;

class CorridorTileLogic implements TileLogicInterface
{
    protected ?CreatureInterface $creature;
    protected RandomEventInterface $event;

    public function __construct(int $mapLevel)
    {
        $scale = ceil(1 + ceil($mapLevel * 0.2));

        // allows more creatures to be spawned on later levels
        // should make SMALL difference
        $maxChance = 700 - $scale;
        $roll = random_int(0, $maxChance);
        if ($roll <= 1) {
            $this->creature = new Dragon($scale);
        } else if ($roll <= 5) {
            $this->creature =  new Golem($scale);
        } else if ($roll <= 100) {
            $this->creature = new Imp($scale);
        } else {
            $this->creature = null;
        }
    }

    public function process(PlayerInterface $player)
    {
        if (random_int(0, 1000) == 0) {
            $this->event = new ThiefArrivedGameEvent($player);
        }
    }

    public function hasAdventureLogMessage(): bool
    {
        return false;
    }

    public function getAdventureLogMessage(): string
    {
        return "";
    }

    public function getAdventureLogMessageClass(): string
    {
        return "";
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