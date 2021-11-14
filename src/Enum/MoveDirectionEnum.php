<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static UP()
 * @method static DOWN()
 * @method static LEFT()
 * @method static RIGHT()
 */
class MoveDirectionEnum extends Enum
{
    private const UP = 'w';
    private const DOWN = 's';
    private const LEFT = 'a';
    private const RIGHT = 'd';
}