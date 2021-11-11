<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static STANDARD()
 * @method static IMPORTANT()
 * @method static SUCCESS()
 * @method static LOOT()
 */
class MessageClassEnum extends Enum
{
    private const STANDARD = 'gray';
    private const IMPORTANT = 'bright-red';
    private const SUCCESS = 'bright-green';
    private const LOOT = 'bright-yellow';
}