<?php

namespace App\Model\Creature;

use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Gold;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Potion\HealthPotion;
use App\Model\Loot\Weapon\Sword;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Player\PlayerInterface;
use Irfa\Gatcha\Roll;

class Imp extends AbstractCommonCreature
{
    private const BASE_STRENGTH = 2;
    private const BASE_ENDURANCE = 2;
    private const BASE_LUCK = 1;
    private const COMMON_NAME = 'Imp';
    private array $lootTable = [
        Sword::class => 1,
        Shield::class => 1,
        HealthPotion::class => 50,
    ];

    public function __construct(int $scale)
    {
        parent::__construct(self::COMMON_NAME, $scale, self::BASE_STRENGTH, self::BASE_ENDURANCE, self::BASE_LUCK);
        $this->scale = $scale;
        $this->experience = $this->createRandomNumberInRangeWithScale(5, 7, $scale);
    }

    public function getLootInventoryBag(PlayerInterface $player): InventoryBagInterface
    {
        if (random_int(0, 10) > 6) {
            $lootItemRoll = Roll::put($this->lootTable)->spin();
            $lootItem = new $lootItemRoll($player->getStats());
            if ($lootItem instanceof LootInterface) {
                $this->loot->addItem(new Gold($this->scale));
                $this->loot->addItem($lootItem);
            } else {
                throw new \LogicException('Loot should always implement LootInterface, in: '.__METHOD__);
            }
        }

        return $this->loot;
    }
}
