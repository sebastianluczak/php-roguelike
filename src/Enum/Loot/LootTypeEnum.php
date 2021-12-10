<?php

namespace App\Enum\Loot;

use MyCLabs\Enum\Enum;

/**
 * @method static WEAPON()
 * @method static ARMOR()
 * @method static KEYSTONE()
 * @method static POTION()
 * @method static GOLD()
 */
class LootTypeEnum extends Enum
{
    private const WEAPON = 'weapon';
    private const ARMOR = 'armor';
    private const KEYSTONE = 'keystone';
    private const POTION = 'potion';
    private const GOLD = 'gold';
}
