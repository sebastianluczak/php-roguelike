<?php

namespace App\Model\Player;

use App\Model\Loot\Weapon\Sword;

class PlayerInventory implements PlayerCoordinatesInterface
{
    protected Sword $bag;
    protected int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }
    public function getY(): int
    {
        return $this->y;
    }
}