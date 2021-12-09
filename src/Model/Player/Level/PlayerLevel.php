<?php

namespace App\Model\Player\Level;

use App\Enum\Player\Level\LevelActionEnum;

class PlayerLevel implements PlayerLevelInterface
{
    protected int $experiencePoints;

    public function __construct()
    {
        $this->experiencePoints = 0;
    }

    public function getLevel(): int
    {
        $level = sqrt($this->experiencePoints) / 7;

        return ceil($level);
    }

    public function getPercentageOfLevelExpProgress(): float
    {
        return ceil((sqrt($this->experiencePoints) / 7 + 1 - ceil(sqrt($this->experiencePoints) / 7)) * 100);
    }

    public function getTotalExperience(): int
    {
        return $this->experiencePoints;
    }

    public function setTotalExperience(int $experience): PlayerLevelInterface
    {
        $this->experiencePoints = $experience;

        return $this;
    }

    public function modifyExperience(int $amount, LevelActionEnum $action)
    {
        switch ($action) {
            case LevelActionEnum::INCREASE():
                $this->experiencePoints = $this->experiencePoints + $amount;
                break;
            case LevelActionEnum::DECREASE():
                $this->experiencePoints = $this->experiencePoints - $amount;
                break;
        }
    }

    public function drawExperienceBar(): string
    {
        $numberOfFilledLinesString = (string) ($this->getPercentageOfLevelExpProgress() / 10);
        $linesToFill = (int) $numberOfFilledLinesString[0];
        $totalLines = 10;
        $feed = '[';
        for ($i = 0; $i <= $linesToFill; ++$i) {
            $feed .= '=';
        }
        for ($j = $linesToFill; $j < $totalLines; ++$j) {
            $feed .= ' ';
        }
        $feed .= '] ';

        return $feed.$this->getPercentageOfLevelExpProgress().'%';
    }
}
