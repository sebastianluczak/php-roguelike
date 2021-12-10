<?php

declare(strict_types=1);

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Creature\Dragon;
use App\Model\Creature\Goblin;
use App\Model\Creature\Golem;
use App\Model\Creature\Imp;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\RandomEvent\ThiefArrivedGameEvent;

class CorridorTileLogic implements TileLogicInterface
{
    protected string $rawMessage;
    protected MessageClassEnum $messageClass;
    protected ?CreatureInterface $creature;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $this->rawMessage = '';
        $this->messageClass = MessageClassEnum::STANDARD();

        // FIXME very important scale! We need to move it to ScaleHelper
        $scale = (int) ceil(1 + ceil($scale * 0.5));

        // allows more creatures to be spawned on later levels
        // should make SMALL difference
        // FIXME check if this is correct
        $maxChance = 700 - $scale;
        if ($maxChance <= 100) {
            $maxChance = 100;
        }
        $roll = random_int(0, $maxChance);
        if ($roll <= 1) {
            $this->creature = new Dragon($scale);
        } elseif ($roll <= 5) {
            $this->creature = new Golem($scale);
        } elseif ($roll <= 50) {
            $this->creature = new Goblin($scale);
        } elseif ($roll <= 200) {
            $this->creature = new Imp($scale);
        } else {
            $this->creature = null;
            $this->rawMessage = 'Nothing happens ...';
        }
    }

    public function process(PlayerInterface $player): void
    {
        if (0 == random_int(0, 1000)) {
            $this->event = new ThiefArrivedGameEvent($player);
        }
    }

    public function hasAdventureLogMessage(): bool
    {
        return !empty($this->rawMessage);
    }

    public function getAdventureLogMessage(): string
    {
        return $this->rawMessage;
    }

    public function getAdventureLogMessageClass(): MessageClassEnum
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
