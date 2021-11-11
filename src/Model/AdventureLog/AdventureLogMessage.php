<?php

namespace App\Model\AdventureLog;

use App\Enum\MessageClassEnum;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AdventureLogMessage implements AdventureLogMessageInterface
{
    protected string $message;
    protected string $rawMessage;
    protected ?string $messageClass;

    public function __construct(string $message, CarbonImmutable $gameStartTime, string $messageClass = null)
    {
        $timestamp = Carbon::now();
        $this->messageClass = $messageClass;
        if ($this->messageClass == null) {
            $this->messageClass = MessageClassEnum::STANDARD();
        }
        $diff = $gameStartTime->diff($timestamp);
        $this->rawMessage = "[" . $diff->format("%H:%I:%S") . "] " . $message . " ";

        $styleBegin = "<fg=" . $this->getImportance() . '>';
        $styleEnd = "</>";
        $this->message = $styleBegin . "[" . $diff->format("%H:%I:%S") . "] " . $message . $styleEnd;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChars(): int
    {
        return strlen($this->rawMessage);
    }

    /**
     * @return string|null
     */
    public function getMessageClass(): string
    {
        return $this->messageClass;
    }

    public function getImportance(): string
    {
        return $this->getMessageClass();
    }
}