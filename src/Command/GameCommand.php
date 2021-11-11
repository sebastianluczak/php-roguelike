<?php

namespace App\Command;

use App\Service\GameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GameCommand extends Command
{
    protected static $defaultName = 'game:start';
    protected GameService $gameService;

    public function __construct(GameService $gameService, string $name = null)
    {
        parent::__construct($name);
        $this->gameService = $gameService;
    }

    protected function configure(): void
    {
        $this->setHelp('Worst Roguelike/ARPG in history, play it now.')
            ->addArgument('dev', InputArgument::OPTIONAL, 'Activate developer mode?', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $devMode = $input->getArgument('dev');
        $input->setInteractive(false);
        $this->clearScreen();
        $this->gameService->run($output, $devMode);

        return Command::SUCCESS;
    }

    private function clearScreen()
    {
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
    }
}