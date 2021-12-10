<?php

namespace App\Model\Loot\Keystone;

use App\Enum\GameIconEnum;

class BrokenKeystone extends AbstractKeystone
{
    protected string $name = 'Broken keystone';

    public function __construct()
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up ".GameIconEnum::GEM().' '.$this->getName();
        $this->dice = '1d1';
    }
}
