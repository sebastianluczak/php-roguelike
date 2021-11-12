<?php

namespace App\Model\Loot\Armor;

use App\Enum\Loot\LootClassEnum;
use DiceBag\DiceBag;

class Shield extends AbstractArmor
{
    protected int $armor;
    protected string $name = "Basic Shield";

    protected array $uniqueNames = [
        'Aegis',
        'Aeneas’ Shield',
        'Ancilia',
        'Hippomedon’s Shield',
        'Jaivardhan',
        'Perseus’ Shield',
        'Shield of Achilles',
        'Shield of Ajax',
        'Srivatsa',
        'Svalinn',
        'Belus’ Shield'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->armor = random_int(1, 3);

        $roll = DiceBag::factory('1d6+4');
        if ($roll->getTotal() > 8) { // unique loot handler
            $this->lootClass = LootClassEnum::S();
            $this->name = $this->uniqueNames[array_rand($this->uniqueNames)];
            $diceRoll = DiceBag::factory('2d8+' . (5 - $this->lootClass->getValue()));
            $this->armor = $diceRoll->getTotal();
        }

    }

    /**
     * @return int
     */
    public function getArmor(): int
    {
        return $this->armor;
    }
}