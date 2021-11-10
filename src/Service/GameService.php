<?php

namespace App\Service;

use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GameService extends ConsoleOutputGameService
{
    protected MapService $mapService;
    protected PlayerService $playerService;
    protected DiceService $diceService;
    protected AdventureLogService $adventureLogService;

    public function __construct(MapService $mapService, PlayerService $playerService, DiceService $diceService, AdventureLogService $adventureLogService)
    {
        $this->mapService = $mapService;
        $this->playerService = $playerService;
        $this->diceService = $diceService;
        $this->adventureLogService = $adventureLogService;
    }

    /**
     * @return MapService
     */
    public function getMapService(): MapService
    {
        return $this->mapService;
    }

    /**
     * @return PlayerService
     */
    public function getPlayerService(): PlayerService
    {
        return $this->playerService;
    }

    /**
     * @return DiceService
     */
    public function getDiceService(): DiceService
    {
        return $this->diceService;
    }

    public function run(OutputInterface $output)
    {
        $gameOver = false;
        while (!$gameOver) {
            if ($this->playerService->getPlayer()->getHealth() <= 0) {
                $gameOver = true;
                break;
            }

            $mapObject = $this->mapService->getMap();
            $this->printPlayerInfo($this->getPlayerService()->getPlayer(), $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            $buttonPressed = $this->getChar();
            try {
                $this->mapService->movePlayer($this->getPlayerService()->getPlayer(), $buttonPressed);
            } catch (NotValidMoveException $e) {
                $output->writeln("Not valid move");
            } catch (NewLevelException $e) {
                $this->getMapService()->increaseMapLevel();
                $this->getMapService()->createNewLevel();
            }
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
        }

        if ($gameOver) {
            $this->printPlayerInfo($this->getPlayerService()->getPlayer(), $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            return Command::SUCCESS;
        }

        return Command::SUCCESS;
    }

    public function handleGameOver(GameOverException $e)
    {
        $this->adventureLogService->createGameOverLog($e);
    }
}