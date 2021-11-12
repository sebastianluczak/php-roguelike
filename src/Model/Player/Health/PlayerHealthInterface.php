<?php

namespace App\Model\Player\Health;

interface PlayerHealthInterface
{
    public function getHealth(): int;
    public function getMaxHealth(): int;
    public function modifyHealth(int $amount): PlayerHealthInterface;
}