<?php

declare(strict_types=1);

namespace App\Message;

use App\Model\Player\PlayerInterface;

class GodModeActivatedMessage implements MessageInterface
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): GodModeActivatedMessage
    {
        $this->player = $player;

        return $this;
    }
}
