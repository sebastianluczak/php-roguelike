<?php

namespace App\Model\Creature\Boss;

use App\Enum\Creature\CreatureClassEnum;
use App\Model\Creature\AbstractBossCreature;
use App\Model\Loot\Gold;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;

class Alfgard extends AbstractBossCreature
{
    private const BASE_STRENGTH = 10;
    private const BASE_ENDURANCE = 9;
    private const BASE_LUCK = 5;
    private const COMMON_NAME = 'Alfgard, Noxious Divinity of Storms';

    public function __construct(int $scale)
    {
        $this->creatureClass = CreatureClassEnum::BOSS();
        parent::__construct(self::COMMON_NAME, $scale, self::BASE_STRENGTH, self::BASE_ENDURANCE, self::BASE_LUCK);
        $this->experience = $this->createRandomNumberInRangeWithScale(150, 250, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        // todo move to calculator
        $this->loot->addItem(new Gold((int) ceil(sqrt($this->scale * 1.2))));

        return $this->loot;
    }
}
