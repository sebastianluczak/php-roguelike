<?php

namespace App\Model\RandomEvent;

use App\Model\RandomEvent\Effect\GameEffectInterface;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

abstract class AbstractRandomEvent implements RandomEventInterface
{
    protected GameEffectInterface $effect;
    protected string $type;
    protected string $description;
    protected CarbonImmutable $lastsFrom;
    protected CarbonInterval $lastsFor;
    protected CarbonImmutable $lastsUntil;

    public function __construct()
    {
        $this->description = "New mysterious event arises...";
    }

    public function getDescription(): string
    {
        return $this->getDescription();
    }

    /**
     * @return GameEffectInterface
     */
    public function getEffect(): GameEffectInterface
    {
        return $this->effect;
    }

    /**
     * @return CarbonImmutable
     */
    public function getLastsFrom(): CarbonImmutable
    {
        return $this->lastsFrom;
    }

    /**
     * @return CarbonInterval
     */
    public function getLastsFor(): CarbonInterval
    {
        return $this->lastsFor;
    }

    /**
     * @return CarbonImmutable
     */
    public function getLastsUntil(): CarbonImmutable
    {
        return $this->lastsUntil;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
