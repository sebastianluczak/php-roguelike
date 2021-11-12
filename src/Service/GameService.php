<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Message\AddAdventureLogMessage;
use App\Message\GameOverMessage;
use App\Message\RegenerateMapMessage;
use App\Model\Player\PlayerInterface;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GameService extends ConsoleOutputGameService
{
    public function run(OutputInterface $output)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage("Game started at " . $this->getInternalClockService()->getGameStartTime()->toFormattedDateString()));
        $this->messageBus->dispatch(new AddAdventureLogMessage("DEVELOPER MODE IS ACTIVE", MessageClassEnum::DEVELOPER()));

        while (true) {
            // todo test
            $this->internalClockService->tick();

            $player = $this->getPlayerService()->getPlayer();
            if ($player->getHealth() <= 0) {
                break;
            }

            $mapObject = $this->mapService->getMap();
            $this->printPlayerInfo($player, $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            $buttonPressed = $this->getPlayerCommand();
            $this->handleUncommonButtonPresses($buttonPressed);

            try {
                $this->mapService->movePlayer($player, $buttonPressed, $this->mapService->getMapLevel());
            } catch (NotValidMoveException $e) {
                $this->messageBus->dispatch(new AddAdventureLogMessage("You can't move in this direction"));
            } catch (NewLevelException $e) {
                // todo move to dispatch queue
                $this->getMapService()->increaseMapLevel();
                $this->getMapService()->createNewLevel();
            }
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
        }

        $this->printPlayerInfo($player, $output);
        $this->printMap($mapObject, $output);
        $this->printLeaderBoards($player);
        $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

        return Command::SUCCESS;
    }

    public function handleGameOver(GameOverException $e, PlayerInterface $player)
    {
        $this->messageBus->dispatch(new GameOverMessage($player, $e->getReason()));
    }

    public function setDevMode(string $devMode = "false")
    {
        $this->devMode = true;
        if ($devMode == "false") {
            $this->devMode = false;
        }
    }

    private function handleUncommonButtonPresses(string $buttonPressed)
    {
        if ($buttonPressed == "q") {
            $this->messageBus->dispatch(new AddAdventureLogMessage("Game exit at " . Carbon::now()->toFormattedDateString()));
            $this->messageBus->dispatch(new GameOverMessage($this->playerService->getPlayer(), "Game exit"));
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
        }

        if ($buttonPressed == "p") {
            $this->messageBus->dispatch(new AddAdventureLogMessage("DEV MODE STATUS: " . $this->isDevMode()));
            if ($this->isDevMode()) {
                $devMode = "false";
            } else {
                $devMode = "true";
            }
            $this->setDevMode($devMode);
            $this->messageBus->dispatch(new AddAdventureLogMessage("Dev mode changed at " . Carbon::now()));
            $this->messageBus->dispatch(new AddAdventureLogMessage("DEV MODE STATUS: " . $this->isDevMode()));
        }

        if ($this->isDevMode()) {
            if ($buttonPressed == "r") {
                $this->messageBus->dispatch(new AddAdventureLogMessage("Map regenerated at " . Carbon::now(), MessageClassEnum::DEVELOPER()));
                $this->messageBus->dispatch(new RegenerateMapMessage());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
            }
            if ($buttonPressed == "l") {
                $this->printLeaderBoards($this->getPlayerService()->getPlayer());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
            }
        }
    }
}