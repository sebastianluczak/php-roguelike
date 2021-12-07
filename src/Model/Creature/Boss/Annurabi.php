<?php

namespace App\Model\Creature\Boss;

use App\Enum\Creature\CreatureClassEnum;
use App\Enum\GameIconEnum;
use App\Model\Creature\Dragon;
use App\Model\Loot\Armor\CreatureGenericArmor;
use App\Model\Loot\Gold;
use App\Model\Loot\Weapon\CreatureMeleeWeapon;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;
use App\Model\Stats\Stats;

class Annurabi extends Dragon
{
    private const BASE_STRENGTH = 8;
    private const BASE_ENDURANCE = 5;
    private const BASE_LUCK = 3;
    private const COMMON_NAME = 'Annurabi';

    public function __construct(int $scale)
    {
        parent::__construct($scale);
        $this->creatureClass = CreatureClassEnum::BOSS();

        $this->stats = new Stats();
        $this->stats->modifyStrength(ceil(self::BASE_STRENGTH * ($this->creatureClass->getValue() + sqrt($scale)) / 100));
        $this->stats->modifyEndurance(ceil(self::BASE_ENDURANCE * ($this->creatureClass->getValue() + sqrt($scale)) / 100));
        $this->stats->modifyLuck(ceil(self::BASE_LUCK * ($this->creatureClass->getValue() + sqrt($scale)) / 100));
        $this->weaponSlot = new CreatureMeleeWeapon($this->stats);
        $this->armorSlot = new CreatureGenericArmor($this->stats);
        $this->name = "<fg=bright-red>" . GameIconEnum::SKULL() . " " . self::COMMON_NAME . "</>";

        $this->health = ceil($this->stats->getEndurance() * ceil($scale/2) * ($this->creatureClass->getValue() / 100));
        $this->experience = $this->createRandomNumberInRangeWithScale(100, 200, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        $this->loot->addItem(new Gold(ceil(sqrt($this->scale))));

        return $this->loot;
    }
}
