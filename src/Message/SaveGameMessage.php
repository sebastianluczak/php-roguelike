<?php

declare(strict_types=1);

namespace App\Message;

use Doctrine\Common\Collections\ArrayCollection;

class SaveGameMessage
{
    protected ArrayCollection $stateOfGame;

    public function __construct(ArrayCollection $stateOfGame)
    {
        $this->stateOfGame = $stateOfGame;
    }

    public function getStateOfGame(): ArrayCollection
    {
        return $this->stateOfGame;
    }
}
