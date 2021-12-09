<?php

namespace App\Model\Dialogue;

use App\Message\MessageInterface;

interface DialogueInterface
{
    public function getEntity(): string;
    public function getText(): string;
    public function getOptions(): array;
    public function print(): string;
    public function handleButtonPress(string $buttonPressed): ?MessageInterface;
}
