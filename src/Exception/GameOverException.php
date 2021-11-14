<?php

namespace App\Exception;

use App\Model\Creature\CreatureInterface;
use Exception;
use Throwable;

class GameOverException extends Exception
{
    protected string $reason;
    protected CreatureInterface $creature;

    public function __construct(CreatureInterface $creature, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->creature = $creature;
        $this->setReason();
    }

    protected function setReason()
    {
        $this->reason = "Killed by: " . $this->creature->getName();
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}