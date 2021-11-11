<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\Gold;
use App\Model\Loot\SkillBoost;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\PleasedTheGodsGameEvent;
use App\Model\RandomEvent\RandomEventInterface;

class ShopTileLogic implements TileLogicInterface
{
    protected SkillBoost $skillBoost;
    protected string $rawMessage;
    protected string $messageClass;
    protected RandomEventInterface $event;

    public function __construct(int $scale)
    {
        $this->skillBoost = new SkillBoost($scale);
    }

    public function process(PlayerInterface $player)
   {
       if ($player->getGold() >= 100) {
           $this->rawMessage = "🧍 You feel rush of energy after paying some gold to strange man";
           $this->messageClass = MessageClassEnum::SUCCESS();

           $player->increaseDamage($this->skillBoost->getDamageAmount());
           $player->increaseArmor($this->skillBoost->getArmorAmount());
           $player->increaseHealth($this->skillBoost->getHealthAmount());
           $player->increaseExperience($this->skillBoost->getExperience());
           $player->decreaseGoldAmount(100);
       } else {
           $this->event = new PleasedTheGodsGameEvent($player);
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