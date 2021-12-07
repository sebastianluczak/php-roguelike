<?php

namespace App\Model\Tile\TileInteraction;

use App\Model\Dialogue\DialogueInterface;

interface TileInteractionInterface
{
    public function getDialogue(): DialogueInterface;
}
