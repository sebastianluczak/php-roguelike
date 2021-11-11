<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Message\AddAdventureLogMessage;
use App\Message\GameOverMessage;
use App\Model\Player\PlayerInterface;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GameService extends ConsoleOutputGameService
{
    public function run(OutputInterface $output, bool $devMode)
    {
        $this->devMode = $devMode;
        $this->messageBus->dispatch(new AddAdventureLogMessage("Game started at " . $this->getInternalClockService()->getGameStartTime()->toDateTimeLocalString()));

        if ($devMode) {
            $this->messageBus->dispatch(new AddAdventureLogMessage("DEVELOPER MODE IS ACTIVE", MessageClassEnum::IMPORTANT()));
        }
        while (true) {
            $player = $this->getPlayerService()->getPlayer();
            if ($player->getHealth() <= 0) {
                break;
            }

            $mapObject = $this->mapService->getMap();
            $this->printPlayerInfo($player, $output);
            $this->printMap($mapObject, $output);
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

            $buttonPressed = $this->getPlayerCommand();
            if ($buttonPressed == "q") {
                $this->messageBus->dispatch(new AddAdventureLogMessage("Game exit at " . Carbon::now()));
                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                break;
            }
            try {
                $this->mapService->movePlayer($player, $buttonPressed, $this->mapService->getMapLevel());
            } catch (NotValidMoveException $e) {
                $this->messageBus->dispatch(new AddAdventureLogMessage("You can't move in this direction"));
            } catch (NewLevelException $e) {
                $this->getMapService()->increaseMapLevel();
                $this->getMapService()->createNewLevel();
            }
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
        }

        $this->printPlayerInfo($player, $output);
        $this->printMap($mapObject, $output);
        $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);
        $this->printGameOverScreen($player, $output);

        return Command::SUCCESS;
    }

    public function handleGameOver(GameOverException $e, PlayerInterface $player)
    {
        $this->messageBus->dispatch(new GameOverMessage($player, $e->getReason()));
    }
}