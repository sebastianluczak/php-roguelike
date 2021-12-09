<?php

namespace App\Model\Stats;

/**
 * Good'ol S.P.E.C.I.A.L stats for each Creature (and Player).
 */
interface StatsInterface
{
    public function getStrength(): int;

    public function getPerception(): int;

    public function getEndurance(): int;

    public function getCharisma(): int;

    public function getIntelligence(): int;

    public function getAgility(): int;

    public function getLuck(): int;

    public function modifyStrength(int $amount = 1): StatsInterface;

    public function modifyPerception(int $amount = 1): StatsInterface;

    public function modifyEndurance(int $amount = 1): StatsInterface;

    public function modifyCharisma(int $amount = 1): StatsInterface;

    public function modifyIntelligence(int $amount = 1): StatsInterface;

    public function modifyAgility(int $amount = 1): StatsInterface;

    public function modifyLuck(int $amount = 1): StatsInterface;

    public function getFormattedStats(): string;
}
