<?php

namespace App\Model\Dialogue;

interface DialogueInterface
{
    public function getText(): string;
    public function getOptions(): array;
}
