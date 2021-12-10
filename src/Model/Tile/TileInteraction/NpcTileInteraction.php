<?php

namespace App\Model\Tile\TileInteraction;

use App\Model\Dialogue\DialogueInterface;
use App\Model\Npc\NpcInterface;
use App\Model\Player\PlayerInterface;

class NpcTileInteraction implements TileInteractionInterface
{
    protected PlayerInterface $player;
    protected NpcInterface $npc;

    public function __construct(PlayerInterface $player, NpcInterface $npc)
    {
        $this->player = $player;
        $this->npc = $npc;
    }

    public function getDialogue(): DialogueInterface
    {
        return $this->npc->getDialogue();
    }
}
