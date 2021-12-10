<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @TODO add all $scale occurrences
 */
final class ScaleHelper extends StatsCalculatorHelper
{
    /**
     * @description Basic scale adds current Map Level to current Player Level
     */
    public static function basicScale(int $mapLevel, int $playerLevel): int
    {
        return $mapLevel + $playerLevel;
    }

    /**
     * @description Multiplies base scale by some number
     *              which should be in range {0.5, 1.5}
     *              to avoid game breaking balance errors
     *
     * @throws ValidationFailedException
     */
    public static function bossEncounterScale(int $scale, float $bossOwnScaleVariable): int
    {
        if ($bossOwnScaleVariable < 0.5 || $bossOwnScaleVariable > 1.5) {
            throw new ValidationFailedException($bossOwnScaleVariable, new ConstraintViolationList());
        }

        return (int) ceil($scale * $bossOwnScaleVariable);
    }
}
