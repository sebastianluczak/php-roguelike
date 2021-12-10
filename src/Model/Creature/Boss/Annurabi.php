<?php

namespace App\Model\Creature\Boss;

use App\Enum\Creature\CreatureClassEnum;
use App\Model\Creature\AbstractBossCreature;
use App\Model\Loot\Gold;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;

class Annurabi extends AbstractBossCreature
{
    private const BASE_STRENGTH = 8;
    private const BASE_ENDURANCE = 5;
    private const BASE_LUCK = 3;
    private const COMMON_NAME = 'Annurabi';

    public function __construct(int $scale)
    {
        $this->creatureClass = CreatureClassEnum::BOSS();
        parent::__construct(self::COMMON_NAME, $scale, self::BASE_STRENGTH, self::BASE_ENDURANCE, self::BASE_LUCK);
        $this->experience = $this->createRandomNumberInRangeWithScale(100, 200, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        $this->loot->addItem(new Gold((int) ceil(sqrt($this->scale))));

        return $this->loot;
    }
}
