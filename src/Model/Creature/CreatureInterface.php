<?php

namespace App\Model\Creature;

interface CreatureInterface
{
    public function getName(): string;
    public function getDamage(): int;
    public function getArmor(): int;
    public function getHealth(): int;
    public function handleLoot();
    public function decreaseHealth(float $playerHitDamage);
    public function getExperience(): int;
}