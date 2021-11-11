<?php

namespace App\Model\Player;

interface PlayerInterface
{
    public function getArmorScore(): int;
    public function setArmorScore(int $armorScore): PlayerInterface;
    public function getDamageScore(): int;
    public function setDamageScore(int $damageScore): PlayerInterface;
    public function increaseHealth(int $healthAmount);
    public function decreaseHealth(float $healthAmount);
    public function getPlayerName(): string;
    public function setGold(int $gold): PlayerInterface;
    public function getGold(): int;
    public function addGoldAmount(int $amount): PlayerInterface;
    public function decreaseGoldAmount(int $amount): PlayerInterface;
    public function increaseDamage(int $amount): PlayerInterface;
    public function increaseArmor(int $amount): PlayerInterface;
    public function getHealth(): int;
    public function getKillCount(): int;
    public function increaseKillCount(): int;
    public function getLevel(): int;
    public function increaseExperience(int $amount);
    public function getExperience(): int;
    public function draw(): string;
    public function setCoordinates(PlayerCoordinatesInterface $coordinates): PlayerInterface;
    public function getCoordinates(): PlayerCoordinatesInterface;
}