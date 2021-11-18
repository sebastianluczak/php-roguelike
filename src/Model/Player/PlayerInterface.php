<?php

namespace App\Model\Player;

use App\Model\Player\Health\PlayerHealthInterface;
use App\Model\Player\Inventory\PlayerInventoryInterface;
use App\Model\Player\Level\PlayerLevelInterface;
use App\Model\Stats\StatsInterface;

interface PlayerInterface
{
    public function getPlayerName(): string;

    /**
     * @deprecated Please use InventoryBag::getGoldAmount()
     * @return int
     */
    public function getGold(): int;
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
    public function getInitiative(): float;
}