<?php

namespace App\Model\Player\Health;

use App\Enum\Player\Health\HealthActionEnum;

interface PlayerHealthInterface
{
    public function getHealth(): int;
    public function getMaxHealth(): int;
    public function modifyHealth(int $amount, HealthActionEnum $action): PlayerHealthInterface;
    public function getWarningThreshold(): int;
    public function increaseMaxHealth(int $amount): PlayerHealthInterface;
}
