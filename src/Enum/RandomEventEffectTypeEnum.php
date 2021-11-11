<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static POSITIVE()
 * @method static NEGATIVE()
 */
class RandomEventEffectTypeEnum extends Enum
{
    private const NEGATIVE = 'negative';
    private const POSITIVE = 'positive';
}