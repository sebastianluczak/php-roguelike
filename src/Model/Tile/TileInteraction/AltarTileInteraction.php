<?php

namespace App\Model\Tile\TileInteraction;

use App\Model\Dialogue\City\AltarDialogue;
use App\Model\Dialogue\DialogueInterface;

class AltarTileInteraction implements TileInteractionInterface
{
    protected DialogueInterface $dialogue;

    public function __construct()
    {
        $this->dialogue = new AltarDialogue();
    }

    public function getDialogue(): DialogueInterface
    {
        return $this->dialogue;
    }
}
