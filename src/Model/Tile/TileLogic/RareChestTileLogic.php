<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\Loot\LootTypeEnum;
use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\AbstractLoot;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Weapon\Sword;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\Stats\StatsInterface;
use Irfa\Gatcha\Roll;

class RareChestTileLogic implements TileLogicInterface
{
    protected string $rawMessage;
    protected string $messageClass;
    protected LootInterface $loot;
    protected RandomEventInterface $event;
    protected array $itemChances = [
        Sword::class => 50,
        Shield::class => 50
    ];

    public function __construct(int $scale, StatsInterface $stats)
    {
        $itemRolled = Roll::put($this->itemChances)->spin();
        /** @var AbstractLoot $itemObject */
        $itemObject = new $itemRolled($stats);

        if ($itemObject instanceof LootInterface) {
            $this->loot = $itemObject;
            switch ($itemObject->getLootType()) {
                case LootTypeEnum::WEAPON():
                    $this->rawMessage = $this->loot->getLootPickupMessage();
                    break;
                case LootTypeEnum::ARMOR():
                    $this->rawMessage = $this->loot->getLootPickupMessage();;
                    break;
            }
        }

        $this->messageClass = MessageClassEnum::LOOT();
    }

    public function process(PlayerInterface $player)
   {
       switch ($this->loot->getLootType()) {
           case LootTypeEnum::WEAPON():
               $player->getInventory()->handleLoot($this->loot);
               if (!$player->getInventory()->hasChanged()) {
                   // todo InventoryBag support
                   $this->rawMessage = "You left " . $this->loot . " on ground, you're better equipped (" . $player->getInventory()->getArmorSlot() . ").";
               }
               break;
           case LootTypeEnum::ARMOR():
               $player->getInventory()->handleLoot($this->loot);
               if (!$player->getInventory()->hasChanged()) {
                   // todo InventoryBag support
                   $this->rawMessage = "You left " . $this->loot . " on ground, you're better equipped (" . $player->getInventory()->getArmorSlot() . ").";
               }
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