<?php

namespace App\Generator\Level;

use App\Model\CityMap;
use App\Model\Tile\City\WallTile;

class DefaultBoxRoomGenerator
{
    protected CityMap $map;
    protected array $roomDefinition;

    public function __construct(CityMap $mapModel, array $roomDefinition)
    {
        $this->map = $mapModel;
        $this->roomDefinition = $roomDefinition;

        $this->createRooms();
    }

    public function createRooms(): void
    {
        for ($x = $this->roomDefinition['starting_point'][0]; $x < $this->roomDefinition['starting_point'][0] + $this->roomDefinition['size'][0]; ++$x) {
            for ($y = $this->roomDefinition['starting_point'][1]; $y < $this->roomDefinition['starting_point'][1] + $this->roomDefinition['size'][1]; ++$y) {
                if ($this->isSpawnableArea($x, $y) && !empty($this->roomDefinition['logic_tile'])) {
                    $roomLogicTile = $this->roomDefinition['logic_tile'];
                    $this->map->replaceTile(new $roomLogicTile(), $x, $y);
                }
                if ($this->isEdgeOfRoom($x, $y)) {
                    if ($x != floor($this->roomDefinition['starting_point'][0] + ($this->roomDefinition['size'][0] / 2))) {
                        if ($y != floor($this->roomDefinition['starting_point'][1] + ($this->roomDefinition['size'][1] / 2))) {
                            $this->map->replaceTile(new WallTile(), $x, $y);
                        }
                    }
                }
            }
        }
    }

    protected function isEdgeOfRoom(int $x, int $y): bool
    {
        $startingPointX = (int) $this->roomDefinition['starting_point'][0];
        $startingPointY = (int) $this->roomDefinition['starting_point'][1];
        $roomSizeX = (int) $this->roomDefinition['size'][0];
        $roomSizeY = (int) $this->roomDefinition['size'][1];

        if ($startingPointX == $x || $startingPointY == $y) {
            return true;
        }

        if ($startingPointX + $roomSizeX == $x + 1 || $startingPointY + $roomSizeY == $y + 1) {
            return true;
        }

        return false;
    }

    public function getMap(): CityMap
    {
        return $this->map;
    }

    private function isSpawnableArea(int $x, int $y): bool
    {
        if ($this->roomDefinition['starting_point'][0] + ceil($this->roomDefinition['size'][0] / 2) == $x &&
            $this->roomDefinition['starting_point'][1] + ceil($this->roomDefinition['size'][1] / 2) - 1 == $y) {
            return true;
        }

        return false;
    }
}
