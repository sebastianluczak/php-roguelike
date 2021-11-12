<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Weapon\Sword;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;

class RareChestTileLogic implements TileLogicInterface
{
    protected LootInterface $loot;
    protected string $rawMessage;
    protected string $messageClass;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $roll = random_int(0, 1000);
        if ($roll >= 500) {
            $this->loot = new Sword();
            $this->rawMessage = "You've picked up 🗡️ " . $this->loot->getName() . "+" . $this->loot->getDamage();
        } else {
            $this->loot = new Shield();
            $this->rawMessage = "You've picked up 🛡️ " . $this->loot->getName() . "+" . $this->loot->getArmor();
        }
        $this->messageClass = MessageClassEnum::LOOT();
    }

    public function process(PlayerInterface $player)
   {
       if ($this->loot->isWeapon()) {
           $player->getInventory()->handleLoot($this->loot);
            // soon becomes deprecated
           $player->increaseDamage($this->loot->getDamage());
       }
       if ($this->loot->isArmor() && $player->getArmorScore() <= 80) {
           $player->getInventory()->handleLoot($this->loot);
           // soon becomes deprecated
           $player->increaseArmor($this->loot->getArmor());
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

    public function hasEncounter(): bool
    {
        return false;
    }

   public function getAdventureLogMessageClass(): string
   {
        return $this->messageClass;
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