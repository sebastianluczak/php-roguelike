<?php

declare(strict_types=1);

namespace App\Model\Dialogue\City;

use App\Message\MessageInterface;
use App\Message\PrayAtTheAltarMessage;
use App\Message\WrongDialogueOptionMessage;
use App\Model\Dialogue\DialogueInterface;

class AltarDialogue implements DialogueInterface
{
    public const DIALOGUE_ENTITY = '𝕲𝖔𝖉𝖘';
    public const DIALOGUE_TEXT = '𝕯𝖔 𝖞𝖔𝖚 𝖜𝖆𝖓𝖙 𝖙𝖔 𝖕𝖗𝖆𝖞 𝖆𝖙 𝖙𝖍𝖊 𝖆𝖑𝖙𝖆𝖗?';
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
                return new PrayAtTheAltarMessage();
            default:
                return new WrongDialogueOptionMessage();
        }
    }
}
