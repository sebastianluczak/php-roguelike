<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static STANDARD()
 * @method static IMPORTANT()
 * @method static SUCCESS()
 * @method static LOOT()
 * @method static DIALOGUE()
 * @method static DEVELOPER()
 */
class MessageClassEnum extends Enum
{
    private const STANDARD = 'gray';
    private const IMPORTANT = 'bright-red';
    private const SUCCESS = 'bright-green';
    private const LOOT = 'bright-yellow';
    private const DIALOGUE = 'bright-cyan';
    private const DEVELOPER = 'bright-blue';
}
