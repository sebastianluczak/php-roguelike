<?php

namespace App\Model\Npc;

use App\Model\Dialogue\DialogueInterface;

interface NpcInterface
{
    public function getDialogue(): DialogueInterface;
}
