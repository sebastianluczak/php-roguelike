<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;
use App\Model\Tile\TileLogic\TileLogicInterface;

class TileLogicMessage
{
    protected PlayerInterface $player;
    protected TileLogicInterface $tileLogic;

    public function __construct(PlayerInterface $player, TileLogicInterface $tileLogic)
    {
        $this->player = $player;
        $this->tileLogic = $tileLogic;
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    /**
     * @return TileLogicInterface
     */
    public function getTileLogic(): TileLogicInterface
    {
        return $this->tileLogic;
    }
}
