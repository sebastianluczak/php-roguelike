<?php

namespace App\Service;

use App\Model\AdventureLog\AdventureLogInterface;

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
}
