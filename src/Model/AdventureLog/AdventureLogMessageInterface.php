<?php

namespace App\Model\AdventureLog;

interface AdventureLogMessageInterface
{
    public function getMessage(): string;
    public function getChars(): int;
    public function getImportance(): ?string;
}
