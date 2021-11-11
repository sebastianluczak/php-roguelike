<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Model\AdventureLog\AdventureLogInterface;
use App\Model\AdventureLog\AdventureLogMessage;

class AdventureLogService
{
    protected AdventureLogInterface $adventureLog;

    public function __construct(AdventureLogInterface $adventureLog)
    {
        $this->adventureLog = $adventureLog;
    }

    /**
     * @return AdventureLogInterface
     */
    public function getAdventureLog(): AdventureLogInterface
    {
        return $this->adventureLog;
    }

    public function createGameOverLog(\App\Exception\GameOverException $e)
    {
        $this->adventureLog->addMessage(new AdventureLogMessage("GAME OVER", MessageClassEnum::IMPORTANT()));
        $this->adventureLog->addMessage(new AdventureLogMessage($e->getReason(), MessageClassEnum::IMPORTANT()));
    }
}