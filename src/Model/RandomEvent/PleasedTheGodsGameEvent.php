<?php

namespace App\Model\RandomEvent;

use App\Enum\RandomEventEffectTypeEnum;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\Effect\RegenHealthEffect;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

class PleasedTheGodsGameEvent extends AbstractRandomEvent
{
    protected int $lastsForSeconds = 10;
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        parent::__construct();
        $this->player = $player;
        $this->effect = new RegenHealthEffect($player);

        $this->type = RandomEventEffectTypeEnum::POSITIVE();
        $this->lastsFrom = CarbonImmutable::now();
        $this->lastsFor = CarbonInterval::seconds($this->lastsForSeconds);
        $this->lastsUntil = $this->lastsFrom->addSeconds($this->lastsForSeconds);
    }

    public function getDescription(): string
    {
        return 'You have pleased the Gods...';
    }

    public function applyEffect(): void
    {
        $this->getEffect()->apply();
    }
}
