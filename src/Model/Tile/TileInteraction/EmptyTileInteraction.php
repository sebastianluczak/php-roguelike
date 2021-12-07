<?php

namespace App\Model\Tile\TileInteraction;

use App\Model\Dialogue\DialogueInterface;
use App\Model\Dialogue\EmptyDialogue;

class EmptyTileInteraction implements TileInteractionInterface
{
    public function getDialogue(): DialogueInterface
    {
        return new EmptyDialogue();
    }
}
