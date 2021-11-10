<?php

namespace App\Service;

use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Message\GameOverMessage;
use App\Model\Player\PlayerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GameService extends ConsoleOutputGameService
{
    public function run(OutputInterface $output)
    {
        while (true) {
            if ($this->playerService->getPlayer()->getHealth() <= 0) {
                break;
            }

            $mapObject = $this->mapService->getMap();
            $this->printPlayerInfo($this->getPlayerService()->getPlayer(), $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            $buttonPressed = $this->getPlayerCommand();
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

        $this->printPlayerInfo($this->getPlayerService()->getPlayer(), $output);
        $this->printMap($mapObject, $output);
        $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);
        $this->printGameOverScreen($this->getPlayerService()->getPlayer(), $output);

        return Command::SUCCESS;
    }

    public function handleGameOver(GameOverException $e, PlayerInterface $player)
    {
        $this->adventureLogService->createGameOverLog($e);
        $this->messageBus->dispatch(new GameOverMessage($player));
    }
}