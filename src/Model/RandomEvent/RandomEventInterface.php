<?php

namespace App\Model\RandomEvent;

use App\Model\RandomEvent\Effect\GameEffectInterface;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

interface RandomEventInterface
{
    public function getDescription(): string;
    public function applyEffect(): void;
    public function getEffect(): GameEffectInterface;
    public function getLastsFrom(): CarbonImmutable;
    public function getLastsFor(): CarbonInterval;
    public function getLastsUntil(): CarbonImmutable;
    public function getType(): string;
}
