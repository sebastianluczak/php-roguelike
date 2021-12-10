<?php

namespace App\Service;

use App\Enum\Game\KeyboardMapEnum;
use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Message\AddAdventureLogMessage;
use App\Message\AdvanceDungeonProgressMessage;
use App\Message\CityPortalMessage;
use App\Message\DevRoomSpawnMessage;
use App\Message\GameOverMessage;
use App\Message\GodModeActivatedMessage;
use App\Message\MessageInterface;
use App\Message\RegenerateMapMessage;
use App\Message\ShowPlayerInventoryMessage;
use App\Message\UseHealingPotionMessage;
use App\Model\Player\PlayerInterface;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GameService extends ConsoleOutputGameService
{
    public function run(OutputInterface $output)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage('Game started at '.$this->getInternalClockService()->getGameStartTime()->toFormattedDateString()));
        $this->messageBus->dispatch(new AddAdventureLogMessage('DEVELOPER MODE IS ACTIVE', MessageClassEnum::DEVELOPER()));

        while (true) {
            $player = $this->getPlayerService()->getPlayer();
            if ($player->getHealth()->getHealth() <= 0) {
                break;
            }
            if ($player->getInDialogue()) {
                // player has dialogue event
                // how to get current dialogue?
                $dialogue = $player->getCurrentDialogue();
                $this->messageBus->dispatch(new AddAdventureLogMessage($dialogue->print(), MessageClassEnum::DIALOGUE()));
                $mapObject = $this->mapService->getMap();
                $this->printPlayerInfo($player, $output);
                $this->printMap($mapObject, $output);
                $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);

                $buttonPressed = $this->getPlayerCommand();
                $message = $dialogue->handleButtonPress($buttonPressed);
                if ($message instanceof MessageInterface) {
                    $message->setPlayer($player);
                    $this->messageBus->dispatch($message);
                    $player->setInDialogue(false);
                    $player->setCurrentDialogue(null);
                }

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
            } else {
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
        }

        if (isset($mapObject)) {
            $this->printPlayerInfo($player, $output);
            $this->printMap($mapObject, $output);
            $this->printLeaderBoards();
            $this->printAdventureLog($this->adventureLogService->getAdventureLog(), $output);
        }

        return Command::SUCCESS;
    }

    public function handleGameOver(GameOverException $e, PlayerInterface $player)
    {
        $this->messageBus->dispatch(new GameOverMessage($player, $e->getReason()));
    }

    public function setDevMode(string $devMode = 'false')
    {
        $this->devMode = true;
        if ('false' == $devMode) {
            $this->devMode = false;
        }
    }

    private function handleUncommonButtonPresses(string $buttonPressed): bool
    {
        if (KeyboardMapEnum::GAME_QUIT() == $buttonPressed) {
            $this->messageBus->dispatch(new AddAdventureLogMessage('Game exit at '.Carbon::now()->toFormattedDateString()));
            $this->playerService->getPlayer()->getHealth()->modifyHealth(
                $this->playerService->getPlayer()->getHealth()->getHealth(),
                HealthActionEnum::DECREASE()
            ); // essentialy kill player
            $this->messageBus->dispatch(
                new GameOverMessage(
                    $this->playerService->getPlayer(),
                    'Suicide by '.$this->playerService->getPlayer()->getInventory()->getWeaponSlot()->getName()
                )
            );

            return true;
        }

        if (KeyboardMapEnum::SHOW_INVENTORY() == $buttonPressed) {
            $this->messageBus->dispatch(new ShowPlayerInventoryMessage($this->playerService->getPlayer()));

            return true;
        }

        if (KeyboardMapEnum::USE_HEALING_POTION() == $buttonPressed) {
            $this->messageBus->dispatch(new UseHealingPotionMessage($this->playerService->getPlayer()));

            return true;
        }

        if (KeyboardMapEnum::DEV_MODE_SWITCH() == $buttonPressed) {
            if ($this->isDevMode()) {
                $devMode = 'false';
            } else {
                $devMode = 'true';
            }
            $this->setDevMode($devMode);

            return true;
        }

        if (KeyboardMapEnum::LEADERBOARDS() == $buttonPressed) {
            $this->printLeaderBoards();

            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

            return true;
        }

        if (KeyboardMapEnum::CITY_PORTAL() == $buttonPressed) {
            $this->messageBus->dispatch(new CityPortalMessage($this->playerService->getPlayer()));

            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

            return true;
        }

        if ($this->isDevMode()) {
            if (KeyboardMapEnum::REGENERATE_MAP() == $buttonPressed) {
                $this->messageBus->dispatch(new AddAdventureLogMessage('Map regenerated at '.Carbon::now(), MessageClassEnum::DEVELOPER()));
                $this->messageBus->dispatch(new RegenerateMapMessage());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }

            if (KeyboardMapEnum::DEV_ROOM_SPAWN() == $buttonPressed) {
                $this->messageBus->dispatch(new AddAdventureLogMessage('[DEV] DevRoom spawned, have fun! At '.Carbon::now(), MessageClassEnum::DEVELOPER()));
                $this->messageBus->dispatch(new DevRoomSpawnMessage());

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }

            if (KeyboardMapEnum::GOD_MODE() == $buttonPressed) {
                $this->messageBus->dispatch(new GodModeActivatedMessage($this->getPlayerService()->getPlayer()));

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }

            if (KeyboardMapEnum::RAISE_DUNGEON_LEVEL() == $buttonPressed) {
                $this->messageBus->dispatch(new AdvanceDungeonProgressMessage($this->getPlayerService()->getPlayer()));

                echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J

                return true;
            }
        }

        return false;
    }
}
