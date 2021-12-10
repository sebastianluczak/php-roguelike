<?php

declare(strict_types=1);

namespace App\Model\Creature;

use App\Enum\GameIconEnum;
use App\Helper\ScaleHelper;
use App\Model\Loot\Armor\CreatureGenericArmor;
use App\Model\Loot\Weapon\CreatureMeleeWeapon;
use App\Model\Stats\Stats;

abstract class AbstractBossCreature extends AbstractCreature
{
    public function __construct(string $creatureName, int $scale, int $baseStrength, int $baseEndurance, int $baseLuck)
    {
        $this->scale = $scale;
        parent::__construct();

        $this->stats = new Stats();
        $this->stats->modifyStrength(ScaleHelper::calculateStatWithBaseAndScale($baseStrength, $scale, $this->creatureClass));
        $this->stats->modifyEndurance(ScaleHelper::calculateStatWithBaseAndScale($baseEndurance, $scale, $this->creatureClass));
        $this->stats->modifyLuck(ScaleHelper::calculateStatWithBaseAndScale($baseLuck, $scale, $this->creatureClass));
        $this->weaponSlot = new CreatureMeleeWeapon($this->stats);
        $this->armorSlot = new CreatureGenericArmor($this->stats);

        $this->name = '<fg=bright-red>'.GameIconEnum::SKULL().' '.$creatureName.'</>';
        $this->health = ScaleHelper::calculateCreatureHealthWithScale($scale, $this->getStats(), $this->getCreatureClass());
    }
}
