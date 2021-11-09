<?php

namespace App\Service;

use App\Exception\GameOverException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;

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

    public function run(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output, QuestionHelper $questionHelper)
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
            $question = new ChoiceQuestion('Direction?',
                [ "UP" => 'w', "DOWN" => 's', "LEFT" => 'a', "RIGHT" => 'd']
            );
            $answer = $questionHelper->ask($input, $output, $question);
            try {
                $this->mapService->movePlayer($this->getPlayerService()->getPlayer(), $answer);
            } catch (NotValidMoveException $e) {
                $output->writeln("Not valid move");
            } catch (NewLevelException $e) {
                $this->getMapService()->increaseMapLevel();
                $this->getMapService()->createNewLevel();
            }
            $output->write(sprintf("\033\143"));
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