<?php

namespace App\Model\Loot;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use DiceBag\DiceBag;

class Gold extends AbstractLoot
{
    protected string $name = 'gold';
    protected int $amount;

    public function __construct(int $scale = 1)
    {
        parent::__construct();

        $this->dice = ceil(sqrt($scale)) . 'd' . random_int($scale, pow($scale, 3)) . '+' . random_int(0, 5 * pow($scale, 2 ));
        $goldDiceRoll = DiceBag::factory($this->dice);
        $this->amount = $goldDiceRoll->getTotal();
        $this->lootType = LootTypeEnum::GOLD();
    }

    public function __toString(): string
    {
        return sprintf(
            "%s %s gold",
            GameIconEnum::GOLD(),
            $this->getName()
        );
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}