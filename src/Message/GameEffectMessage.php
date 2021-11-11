<?php

namespace App\Message;

use App\Model\RandomEvent\RandomEventInterface;

class GameEffectMessage
{
    protected RandomEventInterface $event;

    public function __construct(RandomEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @return RandomEventInterface
     */
    public function getEvent(): RandomEventInterface
    {
        return $this->event;
    }
}