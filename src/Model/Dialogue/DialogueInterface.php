<?php

namespace App\Model\Dialogue;

interface DialogueInterface
{
    public function getEntity(): string;
    public function getText(): string;
    public function getOptions(): array;
    public function print(): string;
}
