<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;

class Dragon extends AbstractCommonCreature
{
    private const BASE_STRENGTH = 8;
    private const BASE_ENDURANCE = 6;
    private const BASE_LUCK = 6;
    private const COMMON_NAME = 'Dragon';

    public function __construct(int $scale)
    {
        parent::__construct(self::COMMON_NAME, $scale, self::BASE_STRENGTH, self::BASE_ENDURANCE, self::BASE_LUCK);
        $this->scale = $scale;
        $this->experience = $this->createRandomNumberInRangeWithScale(20, 50, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        $this->loot->addItem(new Gold((int) ceil(3 * $this->scale)));

        return $this->loot;
    }
}
