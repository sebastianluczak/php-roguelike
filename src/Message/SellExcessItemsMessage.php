<?php
declare(strict_types=1);

namespace App\Message;

use App\Model\Player\PlayerInterface;

class SellExcessItemsMessage implements MessageInterface
{
    protected PlayerInterface $player;

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): SellExcessItemsMessage
    {
        $this->player = $player;

        return $this;
    }
}