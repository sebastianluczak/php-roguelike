<?php

namespace App\Model\AdventureLog;

interface AdventureLogInterface
{
    public function getNewMessages(): array;
    public function getLines(): int;
    public function addMessage(AdventureLogMessageInterface $message);
}