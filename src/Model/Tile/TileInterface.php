<?php

namespace App\Model\Tile;

interface TileInterface
{
    public function isPassable(): bool;
    public function isInteractable(): bool;
    public function isSpawn(): bool;
    public function draw(): string;
    public function hasLogic(): bool;
    public function handleLogic(int $mapLevel);
}