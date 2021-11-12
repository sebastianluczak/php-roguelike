<?php

namespace App\Model\Stats;

class Stats implements StatsInterface
{
    protected int $strength = 1;
    protected int $perception = 1;
    protected int $endurance = 1;
    protected int $charisma = 1;
    protected int $intelligence = 1;
    protected int $agility = 1;
    protected int $luck = 1;

    /**
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @return int
     */
    public function getPerception(): int
    {
        return $this->perception;
    }

    /**
     * @return int
     */
    public function getEndurance(): int
    {
        return $this->endurance;
    }

    /**
     * @return int
     */
    public function getCharisma(): int
    {
        return $this->charisma;
    }

    /**
     * @return int
     */
    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    /**
     * @return int
     */
    public function getAgility(): int
    {
        return $this->agility;
    }

    /**
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }

    public function modifyStrength(int $amount = 1): StatsInterface
    {
        $this->strength = $this->strength + $amount;

        return $this;
    }

    public function modifyPerception(int $amount = 1): StatsInterface
    {
        $this->perception = $this->perception + $amount;

        return $this;
    }

    public function modifyEndurance(int $amount = 1): StatsInterface
    {
        $this->endurance = $this->endurance + $amount;

        return $this;
    }

    public function modifyCharisma(int $amount = 1): StatsInterface
    {
        $this->charisma = $this->charisma + $amount;

        return $this;
    }

    public function modifyIntelligence(int $amount = 1): StatsInterface
    {
        $this->intelligence = $this->intelligence + $amount;

        return $this;
    }

    public function modifyAgility(int $amount = 1): StatsInterface
    {
        $this->agility = $this->agility + $amount;

        return $this;
    }

    public function modifyLuck(int $amount = 1): StatsInterface
    {
        $this->luck = $this->luck + $amount;

        return $this;
    }

    public function getFormattedStats(): string
    {
        return sprintf(
            "S.P.E.C.I.A.L: %s/%s/%s/%s/%s/%s/%s",
            $this->getStrength(),
            $this->getPerception(),
            $this->getEndurance(),
            $this->getCharisma(),
            $this->getIntelligence(),
            $this->getAgility(),
            $this->getLuck()
        );
    }
}