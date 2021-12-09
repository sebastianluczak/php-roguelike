<?php

declare(strict_types=1);

namespace App\Model\Dialogue\City;

use App\Message\MessageInterface;
use App\Message\SellExcessItemsMessage;
use App\Message\WrongDialogueOptionMessage;
use App\Model\Dialogue\DialogueInterface;

class ShopkeeperDialogue implements DialogueInterface
{
    public const DIALOGUE_ENTITY = 'Shopkeeper';
    public const DIALOGUE_TEXT = 'Do you want to sell excess items?';
    public const DIALOGUE_OPTIONS = ['YES', 'no'];

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
        return sprintf('[%s] %s [%s | %s]', self::DIALOGUE_ENTITY, self::DIALOGUE_TEXT, self::DIALOGUE_OPTIONS[0], self::DIALOGUE_OPTIONS[1]);
    }

    public function handleButtonPress(string $buttonPressed): ?MessageInterface
    {
        switch ($buttonPressed) {
            case '1':
                return new SellExcessItemsMessage();
            case '2':
                return new SellExcessItemsMessage();
            default:
                return new WrongDialogueOptionMessage();
        }
    }
}
