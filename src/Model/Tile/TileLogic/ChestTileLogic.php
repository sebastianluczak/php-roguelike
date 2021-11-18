<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\Gold;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;

class ChestTileLogic implements TileLogicInterface
{
    protected Gold $gold;
    protected string $rawMessage;
    protected string $messageClass;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $this->gold = new Gold($scale);
        $this->rawMessage = "You've picked up 💰 " . $this->gold->getAmount() . " gold.";
        $this->messageClass = MessageClassEnum::LOOT();
    }

    public function process(PlayerInterface $player)
   {
       $player->getInventory()->addGoldAmount($this->gold->getAmount());
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