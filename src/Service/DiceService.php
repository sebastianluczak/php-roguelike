<?php

namespace App\Service;

class DiceService
{
    public function roll(int $sides = 6)
    {
        return random_int(1, $sides);
    }
}