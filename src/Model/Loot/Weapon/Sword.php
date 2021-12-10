<?php

namespace App\Model\Loot\Weapon;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootClassEnum;
use App\Model\Stats\Stats;
use DiceBag\DiceBag;

class Sword extends AbstractWeapon
{
    protected string $name = 'Wooden Sword';

    protected array $uniqueNames = [
        'Almace',
        'Arondight',
        'Balmung',
        'Caladbolg',
        'Chandrahas',
        'Claíomh Solais',
        'Curtana',
        'Durendal',
        'Caledflwch',
        'Eckesachs',
        'Sköfnung',
    ];

    public function __construct(Stats $playerStats)
    {
        parent::__construct();
        $this->dice = '1d'.random_int(1, 3 + $playerStats->getLuck()).'+'.(5 - $this->getLootClass()->getValue() + random_int(0, sqrt($playerStats->getLuck())));

        $roll = DiceBag::factory('1d6+4');
        if ($roll->getTotal() > 11 - $playerStats->getLuck()) { // unique loot handler
            $this->lootClass = LootClassEnum::S();
            $this->name = $this->uniqueNames[array_rand($this->uniqueNames)];
            $this->dice = '2d'.random_int(1, 3 + $playerStats->getLuck()).'+'.(5 - $this->getLootClass()->getValue() + random_int(0, sqrt($playerStats->getLuck())));
        }

        $this->lootPickupMessage = "You've picked up ".$this->getFormattedName();
    }

    public function getFormattedName(): string
    {
        return sprintf(
            '%s %s (%s) %s (~%s)',
            GameIconEnum::WEAPON(),
            $this->getName(),
            $this->getLootClass()->getKey(),
            $this->getDice(),
            $this->getAverageRoll()
        );
    }
}
