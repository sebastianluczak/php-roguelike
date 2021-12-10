<?php

declare(strict_types=1);

namespace App\Model\Tile\TileLogic;

use App\Enum\Loot\LootTypeEnum;
use App\Enum\MessageClassEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\AbstractLoot;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Keystone\BrokenKeystone;
use App\Model\Loot\Keystone\ChromaticKeystone;
use App\Model\Loot\Keystone\ColorlessKeystone;
use App\Model\Loot\Keystone\PrismaticKeystone;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Potion\HealthPotion;
use App\Model\Loot\Weapon\Sword;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\Stats\StatsInterface;
use Irfa\Gatcha\Roll;

class RareChestTileLogic implements TileLogicInterface
{
    protected int $scale = 1;
    protected string $rawMessage;
    protected MessageClassEnum $messageClass;
    protected LootInterface $loot;
    protected RandomEventInterface $event;
    protected array $itemChances = [
        Sword::class => 50,
        Shield::class => 50,
        HealthPotion::class => 5,
        PrismaticKeystone::class => 1,
        BrokenKeystone::class => 1,
        ChromaticKeystone::class => 1,
        ColorlessKeystone::class => 1,
    ];

    public function __construct(int $scale, StatsInterface $stats)
    {
        $this->scale = $scale;
        $itemRolled = Roll::put($this->itemChances)->spin();
        /** @var AbstractLoot $itemObject */
        $itemObject = new $itemRolled($stats);

        if ($itemObject instanceof LootInterface) {
            $this->loot = $itemObject;
            switch ($itemObject->getLootType()) {
                case LootTypeEnum::ARMOR():
                case LootTypeEnum::KEYSTONE():
                case LootTypeEnum::WEAPON():
                case LootTypeEnum::POTION():
                    $this->rawMessage = $this->loot->getLootPickupMessage();
                    break;
            }
        }

        $this->messageClass = MessageClassEnum::LOOT();
    }

    public function process(PlayerInterface $player): void
    {
        // TODO, GOOD PRACTICE!!!! Universal via interface
        //$itemInSlot = $player->getInventory()->getSlotOfType($this->loot->getLootType());
        switch ($this->loot->getLootType()) {
            case LootTypeEnum::KEYSTONE():
            case LootTypeEnum::ARMOR():
            case LootTypeEnum::WEAPON():
               $player->getInventory()->handleLoot($this->loot);
               if (!$player->getInventory()->hasChanged()) {
                   $this->rawMessage = 'You put '.$this->loot->getFormattedName()." in bag, you're better equipped (".$player->getInventory()->getSlotOfType($this->loot->getLootType())->getFormattedName().').';
               }
               break;
            case LootTypeEnum::POTION():
               $player->getInventory()->handleLoot($this->loot);
               break;
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

    public function getAdventureLogMessageClass(): MessageClassEnum
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
