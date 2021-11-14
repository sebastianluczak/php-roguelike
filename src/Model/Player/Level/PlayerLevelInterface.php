<?php

namespace App\Model\Player\Level;

use App\Enum\Player\Level\LevelActionEnum;

interface PlayerLevelInterface
{
    public function getLevel(): int;
    public function drawExperienceBar(): string;
    public function getTotalExperience(): int;
    public function setTotalExperience(int $experience): PlayerLevelInterface;
    public function getPercentageOfLevelExpProgress(): float;
    public function modifyExperience(int $amount, LevelActionEnum $action);
}