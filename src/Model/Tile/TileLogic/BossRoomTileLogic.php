<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Model\Creature\Boss\Annurabi;
use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\RandomEvent\ThiefArrivedGameEvent;

class BossRoomTileLogic implements TileLogicInterface
{
    protected int $scale;
    protected string $rawMessage;
    protected string $messageClass;
    protected CreatureInterface $creature;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
        $this->rawMessage = "You've entered a dark room...";
        $this->messageClass = MessageClassEnum::IMPORTANT();
    }

    public function process(PlayerInterface $player)
    {
        if ($player->getInventory()->getKeystone()->getAverageRoll() > 3) {
            // FIXME scale is not fine, maybe should be higher or affected by something more?
            $this->creature = new Annurabi((int) ceil($this->scale * 1.2));
        } else {
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

    public function getAdventureLogMessageClass(): string
    {
        return $this->messageClass;
    }

    public function hasEncounter(): bool
    {
        return !empty($this->creature);
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
