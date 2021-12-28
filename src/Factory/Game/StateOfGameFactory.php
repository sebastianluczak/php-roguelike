<?php
declare(strict_types=1);
namespace App\Factory\Game;
use App\Model\Game\StateOfGameModel;

class StateOfGameFactory
{
    public function create(): StateOfGameModel
    {
        return new StateOfGameModel();
    }
}