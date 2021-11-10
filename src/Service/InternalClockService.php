<?php

namespace App\Service;

use Carbon\CarbonImmutable;

class InternalClockService
{
    protected CarbonImmutable $gameStartTime;

    public function __construct()
    {
        $this->gameStartTime = CarbonImmutable::now();
    }

    /**
     * @return mixed
     */
    public function getGameStartTime(): CarbonImmutable
    {
        return $this->gameStartTime;
    }
}