<?php

namespace App\Model\RandomEvent;

use App\Enum\RandomEventEffectTypeEnum;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\Effect\RemoveGoldEffect;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

class ThiefArrivedGameEvent extends AbstractRandomEvent
{
    protected int $lastsForSeconds = 1;
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        parent::__construct();
        $this->player = $player;
        $this->effect = new RemoveGoldEffect($player);

        $this->type = RandomEventEffectTypeEnum::NEGATIVE();
        $this->lastsFrom = CarbonImmutable::now();
        $this->lastsFor = CarbonInterval::seconds($this->lastsForSeconds);
        $this->lastsUntil = $this->lastsFrom->addSeconds($this->lastsForSeconds);
    }

    public function getDescription(): string
    {
        return 'You spotted a thief searching through your bags.';
    }

    public function applyEffect(): void
    {
        $this->getEffect()->apply();
    }
}
