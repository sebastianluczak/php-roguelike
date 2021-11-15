<?php

namespace App\Command;

use App\Service\GameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GameTestCommand extends Command
{
    protected static $defaultName = 'game:test';
    protected GameService $gameService;

    public function __construct(GameService $gameService, string $name = null)
    {
        parent::__construct($name);
        $this->gameService = $gameService;
    }

    protected function configure(): void
    {
        $this->setHelp('Test of Game');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=#00ff00;bg=#00f>... contents ...</>');

        return Command::SUCCESS;
    }

    private function clearScreen()
    {
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
    }
}