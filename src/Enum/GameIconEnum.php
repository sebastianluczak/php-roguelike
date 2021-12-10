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
 * @method static TIME()
 * @method static STATS()
 * @method static INVENTORY()
 * @method static WEIGHT()
 * @method static SKULL()
 * @method static GEM()
 * @method static POTION()
 */
class GameIconEnum extends Enum
{
    public const DEV_MODE = '🦄';
    public const GOLD = '💰';
    public const HEALTH = '💗';
    public const KILLS = '☠️';
    public const WEAPON = '🗡️';
    public const SHIELD = '🛡️';
    public const BUFFS = '💊';
    public const PLAYER = '🧍';
    public const MAP = '🗺️';
    public const TIME = '⏰';
    public const STATS = '🧠';
    public const INVENTORY = '🧳';
    public const WEIGHT = '🎒';

    public const SKULL = '💀';
    public const GEM = '💎';
    public const POTION = '🧪';
}
