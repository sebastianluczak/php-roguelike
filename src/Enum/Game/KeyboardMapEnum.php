<?php

declare(strict_types=1);

namespace App\Enum\Game;

use MyCLabs\Enum\Enum;

/**
 * @method static GAME_QUIT()
 * @method static SHOW_INVENTORY()
 * @method static USE_HEALING_POTION()
 * @method static DEV_MODE_SWITCH()
 * @method static LEADERBOARDS()
 * @method static CITY_PORTAL()
 * @method static GOD_MODE()
 * @method static RAISE_DUNGEON_LEVEL()
 * @method static DEV_ROOM_SPAWN()
 * @method static REGENERATE_MAP()
 */
class KeyboardMapEnum extends Enum
{
    private const GAME_QUIT = 'q';
    private const SHOW_INVENTORY = 'i';
    private const USE_HEALING_POTION = 'h';
    private const DEV_MODE_SWITCH = 'p';
    private const LEADERBOARDS = 'l';
    private const CITY_PORTAL = 't';
    //region dev-mode
    private const GOD_MODE = 'g';
    private const RAISE_DUNGEON_LEVEL = 'k';
    private const DEV_ROOM_SPAWN = 'm';
    private const REGENERATE_MAP = 'r';
}
