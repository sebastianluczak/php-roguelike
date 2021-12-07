<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;
use App\Model\Tile\TileInteraction\TileInteractionInterface;
use App\Model\Tile\TileLogic\TileLogicInterface;

class TileInteractionMessage
{
    protected PlayerInterface $player;
    protected TileInteractionInterface $tileInteraction;

    public function __construct(PlayerInterface $player, TileInteractionInterface $tileInteraction)
    {
        $this->player = $player;
        $this->tileInteraction = $tileInteraction;
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    /**
     * @return TileInteractionInterface
     */
    public function getTileInteraction(): TileInteractionInterface
    {
        return $this->tileInteraction;
    }
}
