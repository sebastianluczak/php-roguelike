<?php

namespace App\Model\Player;

interface PlayerCoordinatesInterface
{
    public function getX(): int;
    public function getY(): int;
}
