<?php

namespace App\Model\Loot;

class Gold
{
    protected int $amount;

    public function __construct(int $scale = 1)
    {
        $this->amount = random_int(5 * $scale, 20 * $scale);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}