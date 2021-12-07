<?php

namespace App\Model\Npc\City;

use App\Model\Dialogue\City\ShopkeeperDialogue;
use App\Model\Dialogue\DialogueInterface;
use App\Model\Npc\NpcInterface;

class Shopkeeper implements NpcInterface
{
    protected DialogueInterface $dialogue;

    public function __construct()
    {
        $this->dialogue = new ShopkeeperDialogue();
    }

    public function getDialogue(): DialogueInterface
    {
        return $this->dialogue;
    }
}
