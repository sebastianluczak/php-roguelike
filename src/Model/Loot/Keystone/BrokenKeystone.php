<?php

namespace App\Model\Loot\Keystone;

class BrokenKeystone extends AbstractKeystone
{
    protected string $name = 'Broken keystone';

    public function __construct()
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up ".$this->getFormattedName();
        $this->dice = '1d1';
    }
}
