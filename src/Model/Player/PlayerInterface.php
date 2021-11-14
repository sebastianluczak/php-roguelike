<?php

namespace App\Model\Player;

use App\Model\Player\Health\PlayerHealthInterface;
use App\Model\Player\Inventory\PlayerInventoryInterface;
use App\Model\Player\Level\PlayerLevelInterface;
use App\Model\Stats\StatsInterface;

interface PlayerInterface
{
    public function getArmorScore(): int;
    public function setArmorScore(int $armorScore): PlayerInterface;
    public function getDamageScore(): int;
    public function setDamageScore(int $damageScore): PlayerInterface;
    public function getPlayerName(): string;
    public function setGold(int $gold): PlayerInterface;
    public function getGold(): int;
    public function addGoldAmount(int $amount): PlayerInterface;
    public function decreaseGoldAmount(int $amount): PlayerInterface;
    public function increaseDamage(int $amount): PlayerInterface;
    public function increaseArmor(int $amount): PlayerInterface;
    public function getHealth(): PlayerHealthInterface;
    public function getKillCount(): int;
    public function increaseKillCount(): int;
    public function getLevel(): PlayerLevelInterface;
    public function draw(): string;
    public function setCoordinates(PlayerCoordinatesInterface $coordinates): PlayerInterface;
    public function getCoordinates(): PlayerCoordinatesInterface;
    public function setMapLevel(int $mapLevel): PlayerInterface;
    public function getMapLevel(): int;
    public function getStats(): StatsInterface;
    public function getInventory(): PlayerInventoryInterface;
}