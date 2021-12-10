<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;

class Goblin extends AbstractCommonCreature
{
    private const BASE_STRENGTH = 3;
    private const BASE_ENDURANCE = 3;
    private const BASE_LUCK = 1;
    private const COMMON_NAME = 'Goblin';

    public function __construct(int $scale)
    {
        parent::__construct(self::COMMON_NAME, $scale, self::BASE_STRENGTH, self::BASE_ENDURANCE, self::BASE_LUCK);
        $this->scale = $scale;
        $this->experience = $this->createRandomNumberInRangeWithScale(20, 50, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        $this->loot->addItem(new Gold($this->scale));

        return $this->loot;
    }
}
