<?php

declare(strict_types=1);

namespace App\Message;

use App\Model\Player\PlayerInterface;

interface MessageInterface
{
    public function getPlayer(): PlayerInterface;

    public function setPlayer(PlayerInterface $player): self;
}
