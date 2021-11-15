<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Message\AddAdventureLogMessage;
use App\Message\DevRoomSpawnMessage;
use App\Message\GameOverMessage;
use App\Message\RegenerateMapMessage;
use App\Message\ShowPlayerInventoryMessage;
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
            $player = $this->getPlayerService()->getPlayer();
            if ($player->getHealth()->getHealth() <= 0) {
                break;
            }
            $this->internalClockService->tick();

            $mapObject = $this->mapService->getMap();
            $this->printPlayerInfo($player, $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            $buttonPressed = $this->getPlayerCommand();
            if (!$this->handleUncommonButtonPresses($buttonPressed)) {
                try {
                    $this->mapService->movePlayer($player, $buttonPressed, $this->mapService->getMapLevel());
                } catch (NotValidMoveException $e) {
                    $this->messageBus->dispatch(new AddAdventureLogMessage("You can't move in this direction"));
                } catch (NewLevelException $e) {
                    // todo move to dispatch queue
                    $this->getMapService()->increaseMapLevel();
                    $this->getMapService()->createNewLevel();
                }
            }
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
        }

        $this->printPlayerInfo($player, $output);
        $this->printMap($mapObject, $output);
        $this->printLeaderBoards();
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

    private function handleUncommonButtonPresses(string $buttonPressed): bool
    {
        if ($buttonPressed == "q") {
            $this->messageBus->dispatch(new AddAdventureLogMessage("Game exit at " . Carbon::now()->toFormattedDateString()));
            $this->playerService->getPlayer()->getHealth()->modifyHealth(
                $this->playerService->getPlayer()->getHealth()->getHealth(),
                HealthActionEnum::DECREASE()
            ); // essentialy kill player
            $this->messageBus->dispatch(
                new GameOverMessage(
                    $this->playerService->getPlayer(),
                    "Suicide by " . $this->playerService->getPlayer()->getInventory()->getWeaponSlot()->getName()
                )
            );

            return true;
        }

        if ($buttonPressed == "i") {
            $this->messageBus->dispatch(new ShowPlayerInventoryMessage($this->playerService->getPlayer()));

            return true;
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

            return true;
        }

        if ($buttonPressed == "l") {
            $this->printLeaderBoards();

            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

            return true;
        }

        if ($this->isDevMode()) {
            if ($buttonPressed == "r") {
                $this->messageBus->dispatch(new AddAdventureLogMessage("Map regenerated at " . Carbon::now(), MessageClassEnum::DEVELOPER()));
                $this->messageBus->dispatch(new RegenerateMapMessage());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }

            if ($buttonPressed == "m") {
                $this->messageBus->dispatch(new AddAdventureLogMessage("[DEV] DevRoom spawned, have fun! At " . Carbon::now(), MessageClassEnum::DEVELOPER()));
                $this->messageBus->dispatch(new DevRoomSpawnMessage());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }
        }

        return false;
    }
}