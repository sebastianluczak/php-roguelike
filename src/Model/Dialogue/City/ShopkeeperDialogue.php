<?php

namespace App\Model\Dialogue\City;

use App\Model\Dialogue\DialogueInterface;

class ShopkeeperDialogue implements DialogueInterface
{
    public const DIALOGUE_TEXT = "You want to buy or sell something?";
    public const DIALOGUE_OPTIONS = [ 'Sell' , 'Buy' ];

    public function getText(): string
    {
        return self::DIALOGUE_TEXT;
    }

    public function getOptions(): array
    {
        return self::DIALOGUE_OPTIONS;
    }
}
