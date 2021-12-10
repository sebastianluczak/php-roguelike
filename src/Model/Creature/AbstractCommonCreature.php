<?php

declare(strict_types=1);

namespace App\Model\Creature;

use App\Enum\Creature\CreatureClassEnum;
use App\Enum\GameIconEnum;
use App\Helper\ScaleHelper;
use App\Model\Loot\Armor\CreatureGenericArmor;
use App\Model\Loot\Weapon\CreatureMeleeWeapon;
use App\Model\Stats\Stats;

abstract class AbstractCommonCreature extends AbstractCreature
{
    public function __construct(string $creatureName, int $scale, int $baseStrength, int $baseEndurance, int $baseLuck)
    {
        parent::__construct();

        // in fact, those methods should be different to those used in AbstractBossCreature
        $this->stats = new Stats();
        $this->stats->modifyStrength(ScaleHelper::calculateStatWithBaseAndScale($baseStrength, $scale, $this->creatureClass));
        $this->stats->modifyEndurance(ScaleHelper::calculateStatWithBaseAndScale($baseEndurance, $scale, $this->creatureClass));
        $this->stats->modifyLuck(ScaleHelper::calculateStatWithBaseAndScale($baseLuck, $scale, $this->creatureClass));
        $this->weaponSlot = new CreatureMeleeWeapon($this->stats);
        $this->armorSlot = new CreatureGenericArmor($this->stats);

        if ($this->creatureClass == CreatureClassEnum::ELITE() || $this->creatureClass == CreatureClassEnum::LEGENDARY()) {
            $this->name = '<fg=yellow>'.GameIconEnum::SKULL().' '.$this->creatureClass->getKey().' '.$creatureName.' '.$this->getRawName().'</>';
        } else {
            $this->name = $creatureName.' - '.$this->getRawName();
        }
        $this->health = ScaleHelper::calculateCreatureHealthWithScale($scale, $this->getStats(), $this->getCreatureClass());
    }
}
