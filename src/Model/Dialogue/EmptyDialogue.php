<?php

namespace App\Model\Dialogue;

use App\Message\MessageInterface;

class EmptyDialogue implements DialogueInterface
{
    public function getEntity(): string
    {
        return '';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getText(): string
    {
        return '';
    }

    public function print(): string
    {
        return '';
    }

    public function handleButtonPress(string $buttonPressed): ?MessageInterface
    {
        return null;
    }
}
