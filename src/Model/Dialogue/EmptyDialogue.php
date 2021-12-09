<?php

namespace App\Model\Dialogue;

class EmptyDialogue implements DialogueInterface
{
    public function getEntity(): string
    {
        return "";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getText(): string
    {
        return "";
    }

    public function print(): string
    {
        return "";
    }
}
