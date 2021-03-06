<?php

namespace App\Model\Loot\Armor;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootClassEnum;
use App\Model\Stats\Stats;
use DiceBag\DiceBag;

class Shield extends AbstractArmor
{
    protected int $armor;
    protected string $name = 'Basic Shield';
    protected string $dice;

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
        'Belus’ Shield',
    ];

    public function __construct(Stats $playerStats)
    {
        parent::__construct();
        $this->armor = random_int(1, 3);
        $this->dice = '1d'.random_int(1, 3 + $playerStats->getLuck()).'+'.(5 - $this->getLootClass()->getValue() + random_int(0, (int) ceil(sqrt($playerStats->getLuck()))));

        $roll = DiceBag::factory('1d6+4');
        if ($roll->getTotal() > 11 - $playerStats->getLuck()) { // unique loot handler
            $this->lootClass = LootClassEnum::S();
            $this->name = $this->uniqueNames[array_rand($this->uniqueNames)];
            $diceRoll = DiceBag::factory('2d8+'.(5 - $this->lootClass->getValue()));
            $this->armor = $diceRoll->getTotal();
            $this->dice = '2d'.random_int(1, 3 + $playerStats->getLuck()).'+'.(5 - $this->getLootClass()->getValue() + random_int(0, (int) ceil(sqrt($playerStats->getLuck()))));
        }

        $this->priceValue = (int) (ceil(10 * $this->getMaxRollValue() * $this->getAverageRoll() / $this->getMaxRollValue()));
        $this->lootPickupMessage = "You've picked up ".$this->getFormattedName();
    }

    public function getFormattedName(): string
    {
        return sprintf(
            '%s %s (%s) %s (~%s)',
            GameIconEnum::SHIELD(),
            $this->getName(),
            $this->getLootClass()->getKey(),
            $this->getDice(),
            $this->getAverageRoll()
        );
    }
}
