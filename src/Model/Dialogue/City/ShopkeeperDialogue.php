<?php
declare(strict_types=1);

namespace App\Model\Dialogue\City;

use App\Model\Dialogue\DialogueInterface;

class ShopkeeperDialogue implements DialogueInterface
{
    public const DIALOGUE_ENTITY = "Shopkeeper";
    public const DIALOGUE_TEXT = "You want to buy or sell something?";
    public const DIALOGUE_OPTIONS = [ 'Sell' , 'Buy' ];

    public function getEntity(): string
    {
        return self::DIALOGUE_ENTITY;
    }

    public function getText(): string
    {
        return self::DIALOGUE_TEXT;
    }

    public function getOptions(): array
    {
        return self::DIALOGUE_OPTIONS;
    }

    public function print(): string
    {
        return sprintf("[%s] %s", self::DIALOGUE_ENTITY, self::DIALOGUE_TEXT);
    }
}