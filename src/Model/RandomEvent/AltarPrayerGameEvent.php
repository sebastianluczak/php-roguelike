<?php

namespace App\Model\RandomEvent;

use App\Enum\RandomEventEffectTypeEnum;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\Effect\RegenHealthEffect;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

class AltarPrayerGameEvent extends AbstractRandomEvent
{
    protected int $lastsForSeconds = 10;
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        parent::__construct();
        $this->player = $player;
        // todo add changeable events
        $this->effect = new RegenHealthEffect($player, sqrt($player->getStats()->getIntelligence()));
        $this->lastsForSeconds = (int) ($this->lastsForSeconds + ceil(sqrt($player->getStats()->getIntelligence())));

        $this->type = RandomEventEffectTypeEnum::POSITIVE();
        $this->lastsFrom = CarbonImmutable::now();
        $this->lastsFor = CarbonInterval::seconds($this->lastsForSeconds);
        $this->lastsUntil = $this->lastsFrom->addSeconds($this->lastsForSeconds);
    }

    public function getDescription(): string
    {
        return 'You feel rush of energy flowing through your body';
    }

    public function applyEffect(): void
    {
        $this->getEffect()->apply();
    }
}
