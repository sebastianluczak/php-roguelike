<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static DEV_MODE()
 * @method static GOLD()
 * @method static HEALTH()
 * @method static KILLS()
 * @method static WEAPON()
 * @method static SHIELD()
 * @method static BUFFS()
 * @method static PLAYER()
 * @method static MAP()
 */
class GameIconEnum extends Enum
{
    private const DEV_MODE = '🦄';
    private const GOLD = '💰';
    private const HEALTH = '💗';
    private const KILLS = '☠️';
    private const WEAPON = '🗡️';
    private const SHIELD = '🛡️';
    private const BUFFS = '💊';
    private const PLAYER = '🧍';
    private const MAP = '🗺️';
    private const TIME = '⏰';
}