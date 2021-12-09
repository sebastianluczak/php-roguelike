<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * ShowPlayerInventoryHandler::__invoke().
 */
class ShowPlayerInventoryMessage
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
}
