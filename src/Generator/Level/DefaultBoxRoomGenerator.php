<?php

namespace App\Generator\Level;

use App\Model\Map;
use App\Model\Tile\City\WallTile;

class DefaultBoxRoomGenerator
{
    protected Map $map;
    protected array $roomDefinition;

    public function __construct(Map $mapModel, array $roomDefinition)
    {
        $this->map = $mapModel;
        $this->roomDefinition = $roomDefinition;

        $this->createRooms();
    }

    public function createRooms()
    {
        for ($x = $this->roomDefinition['starting_point'][0]; $x < $this->roomDefinition['starting_point'][0] + $this->roomDefinition['size'][0]; $x++) {
            for ($y = $this->roomDefinition['starting_point'][1]; $y < $this->roomDefinition['starting_point'][1] + $this->roomDefinition['size'][1]; $y++) {
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

    protected function isEdgeOfRoom(int $x, int $y)
    {
        if ($this->roomDefinition['starting_point'][0] == $x || $this->roomDefinition['starting_point'][1] == $y) {
            return true;
        }

        if ($this->roomDefinition['starting_point'][0] + $this->roomDefinition['size'][0] == $x + 1 || $this->roomDefinition['starting_point'][1] + $this->roomDefinition['size'][1] == $y + 1) {
            return true;
        }

        return false;
    }

    /**
     * @return Map
     */
    public function getMap(): Map
    {
        return $this->map;
    }
}