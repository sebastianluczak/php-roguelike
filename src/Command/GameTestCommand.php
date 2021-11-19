<?php

namespace App\Command;

use App\Generator\Level\DefaultBoxRoomGenerator;
use App\Model\CityMap;
use App\Model\Map;
use App\Model\Tile\AbstractTile;
use App\Service\GameService;
use App\Service\YamlLevelParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GameTestCommand extends Command
{
    protected static $defaultName = 'game:test';
    protected YamlLevelParserService $levelParserService;

    public function __construct(YamlLevelParserService $levelParserService, string $name = null)
    {
        parent::__construct($name);
        $this->levelParserService = $levelParserService;
    }

    protected function configure(): void
    {
        $this->setHelp('Test of Game');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $yamlFilePath = "resources/levels/Rivermouth_city.yaml";
        $this->levelParserService->setYamlFilePath($yamlFilePath);
        $mapGeneric = new CityMap(
            $this->levelParserService->getMapWidth(),
            $this->levelParserService->getMapHeight(),
            $this->levelParserService->getMapName()
        );
        $rooms = $this->levelParserService->getRoomsDefinitions();
        foreach ($rooms as $roomName => $roomDefinition) {
            /** @var DefaultBoxRoomGenerator $generator */
            $generator = new $roomDefinition['generation_class']($mapGeneric, $roomDefinition);
            $mapGeneric = $generator->getMap();
        }

        $this->clearScreen();
        $this->printMap($mapGeneric, $output);

        return Command::SUCCESS;
    }

    protected function printMap(Map $map, OutputInterface $output)
    {
        foreach ($map->getMapInstance() as $column) {
            //dump('sadsad');
            /**
             * @var AbstractTile $item
             * @var  $value */
            foreach ($column as $value) {
                $output->write($value->draw());
            }
            $output->writeln('');
        }
    }

    private function clearScreen()
    {
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
    }
}