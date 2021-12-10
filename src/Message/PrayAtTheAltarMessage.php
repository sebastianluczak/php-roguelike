<?php

declare(strict_types=1);

namespace App\Message;

use App\Model\Player\PlayerInterface;

class PrayAtTheAltarMessage implements MessageInterface
{
    protected PlayerInterface $player;

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): PrayAtTheAltarMessage
    {
        $this->player = $player;

        return $this;
    }
}
