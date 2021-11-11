<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class MoveDirectionEnum extends Enum
{
    private const UP = 'w';
    private const DOWN = 's';
    private const LEFT = 'a';
    private const RIGHT = 'd';
}