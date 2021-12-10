<?php

namespace App\Model\Stats;

class Stats implements StatsInterface
{
    /**
     *  Affects:
     *  - @TODO base dmg (melee + weapons based of melee)
     *  - carrying weight.
     */
    protected int $strength = 1;

    /**
     *  Affects:
     *  - Initiative
     *  - @TODO currently nothing.
     */
    protected int $perception = 1;

    /**
     *  Affects:
     *  - @TODO currently nothing
     *  - Dice sides in CreatureGenericArmor, affecting damage reduction of player hits
     *  - Rolls in damage reduction from creatures during fights.
     */
    protected int $endurance = 1;

    /**
     *  Affects:
     *  - price of shop/strange-man.
     */
    protected int $charisma = 1;

    /**
     *  Affects:
     *  - @TODO scrolls requirements
     *  - strength of Game Effects
     *  - SkillBoost.
     */
    protected int $intelligence = 1;

    /**
     *  Affects:
     *  - @TODO currently nothing.
     */
    protected int $agility = 1;

    /**
     *  Affects:
     *  - chance of getting better loot
     *  - @TODO chance of getting additional loot.
     */
    protected int $luck = 1;

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function getPerception(): int
    {
        return $this->perception;
    }

    public function getEndurance(): int
    {
        return $this->endurance;
    }

    public function getCharisma(): int
    {
        return $this->charisma;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function getAgility(): int
    {
        return $this->agility;
    }

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
            'S.P.E.C.I.A.L: %s/%s/%s/%s/%s/%s/%s',
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
