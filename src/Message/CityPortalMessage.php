<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * CityPortalHandler::__invoke()
 */
class CityPortalMessage
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}
