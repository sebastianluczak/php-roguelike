<?php

namespace App\Model;

use App\Model\Tile\City\PavementTile;

class CityMap extends Map
{
    protected function createMapInstance(): void
    {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $this->mapInstance[$i][$j] = new PavementTile();
            }
        }
    }
}