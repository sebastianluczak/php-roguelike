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
    public const DEV_MODE = 'π¦';
    public const GOLD = 'π°';
    public const HEALTH = 'π';
    public const KILLS = 'β οΈ';
    public const WEAPON = 'π‘οΈ';
    public const SHIELD = 'π‘οΈ';
    public const BUFFS = 'π';
    public const PLAYER = 'π§';
    public const MAP = 'πΊοΈ';
    public const TIME = 'β°';
    public const STATS = 'π§ ';
    public const INVENTORY = 'π§³';
    public const WEIGHT = 'π';

    public const SKULL = 'π';
    public const GEM = 'π';
    public const POTION = 'π§ͺ';
}
