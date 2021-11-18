<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\GameIconEnum;
use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\SkillBoost;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\AltarPrayerGameEvent;
use App\Model\RandomEvent\RandomEventInterface;

class AltarTileLogic implements TileLogicInterface
{
    protected SkillBoost $skillBoost;
    protected string $rawMessage;
    protected string $messageClass;
    protected RandomEventInterface $event;

    public function process(PlayerInterface $player)
   {
       if ($player->getGold() > 10) {
           $thrownGold = random_int(1, $player->getGold());
           $player->getInventory()->subtractGoldAmount($thrownGold);
           $this->rawMessage = "🧍 You've thrown " . GameIconEnum::GOLD() . " " . $thrownGold . " gold into the altar.";
           $this->messageClass = MessageClassEnum::LOOT();

           $roll = random_int(1, 10) + sqrt($player->getStats()->getIntelligence());
           if ($roll >= 5) {
               $this->event = new AltarPrayerGameEvent($player);
           }
       } else {
           $this->rawMessage = "🧍 You've angered the gods...";
           $this->messageClass = MessageClassEnum::IMPORTANT();
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