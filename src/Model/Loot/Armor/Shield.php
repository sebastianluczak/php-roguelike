<?php

namespace App\Model\Loot\Armor;

class Shield extends AbstractArmor
{
    protected int $armor;

    public function __construct()
    {
        $this->armor = random_int(1, 3);
    }

    /**
     * @return int
     */
    public function getArmor(): int
    {
        return $this->armor;
    }

    public function getName(): string
    {
        return "Shield";
    }
}