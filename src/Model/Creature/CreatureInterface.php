<?php

namespace App\Model\Creature;

use App\Enum\Creature\CreatureClassEnum;
use App\Model\Loot\LootInterface;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;
use App\Model\Stats\StatsInterface;

interface CreatureInterface
{
    public function getName(): string;
    public function getHealth(): int;
    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface;
    public function decreaseHealth(int $playerHitDamage);
    public function getExperience(): int;
    public function getScale(): int;
    public function getStats(): StatsInterface;
    public function getWeaponSlot(): LootInterface;
    public function getArmorSlot(): LootInterface;
    public function getCreatureClass(): CreatureClassEnum;
    public function getInitiative(): float;
}
