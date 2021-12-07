<?php

namespace App\Model\Creature;

use App\Enum\Creature\CreatureClassEnum;
use App\Enum\GameIconEnum;
use App\Model\Loot\Armor\CreatureGenericArmor;
use App\Model\Loot\Gold;
use App\Model\Loot\Weapon\CreatureMeleeWeapon;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;
use App\Model\Stats\Stats;

class Goblin extends AbstractCreature
{
    private const BASE_STRENGTH = 2.5;
    private const BASE_ENDURANCE = 2.5;
    private const BASE_LUCK = 1;
    private const COMMON_NAME = 'Goblin';

    public function __construct(int $scale)
    {
        parent::__construct();
        $this->scale = $scale;

        $this->stats = new Stats();
        $this->stats->modifyStrength(ceil(self::BASE_STRENGTH * ($this->creatureClass->getValue() / 100)) * sqrt($scale));
        $this->stats->modifyEndurance(ceil(self::BASE_ENDURANCE * ($this->creatureClass->getValue() / 100)) * sqrt($scale));
        $this->stats->modifyLuck(ceil(self::BASE_LUCK * ($this->creatureClass->getValue() / 100)) * sqrt($scale));
        $this->weaponSlot = new CreatureMeleeWeapon($this->stats);
        $this->armorSlot = new CreatureGenericArmor($this->stats);

        if ($this->creatureClass == CreatureClassEnum::ELITE() || $this->creatureClass == CreatureClassEnum::LEGENDARY()) {
            $this->name = "<fg=yellow>" . GameIconEnum::SKULL() . " " . $this->creatureClass->getKey() . " " . self::COMMON_NAME . " " . $this->getRawName() . "</>";
        } else {
            $this->name = self::COMMON_NAME . " - " . $this->getRawName();
        }
        $this->health = ceil($this->stats->getEndurance() * ceil($scale/2) * ($this->creatureClass->getValue() / 100));
        $this->experience = $this->createRandomNumberInRangeWithScale(20, 50, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        $this->loot->addItem(new Gold($this->scale));

        return $this->loot;
    }
}
