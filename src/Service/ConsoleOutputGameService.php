<?php

namespace App\Service;

use App\Model\AdventureLog\AdventureLogInterface;
use App\Model\AdventureLog\AdventureLogMessageInterface;
use App\Model\Map;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutputGameService
{
    protected function printMap(Map $map, OutputInterface $output)
    {
        foreach ($map->getMapInstance() as $row => $column) {
            /**
             * @var AbstractTile $item
             * @var  $value */
            foreach ($column as $item => $value) {
                $output->write($value->draw());
            }
            $output->writeln('');
        }
    }

    protected function printPlayerInfo(PlayerInterface $player, OutputInterface $output)
    {
        $output->writeln(
            "--== HEALTH: " . $player->getHealth() .
            " ==-- --== GOLD: " . $player->getGold() .
            "g ==-- --== KILLS: " . $player->getKillCount() .
            " ==-- --== LEVEL: " . $player->getLevel() . " (" . $player->getExperience() . "/100)" .
            " ==-- --== DAMAGE: " . $player->getDamageScore() .
            " ==-- --== ARMOR: " . $player->getArmorScore() .
            "% ==-- --== DUNGEON DEPTH: " . $this->mapService->getMapLevel() . " ==--");
    }

    protected function printAdventureLog(AdventureLogInterface $adventureLog, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $lines = $adventureLog->getNewLines();

        $output->writeln('<fg=green>');
        $output->writeln("+=============================================================================+");
        if ($lines == 0) {
            for ($i = 0; $i <= 3; $i++) {
                $output->writeln("|                                                                             |");
            }
        } else {
            /** @var AdventureLogMessageInterface $newMessage */
            $linesPrinted = 0;
            foreach ($adventureLog->getNewMessages() as $newMessage) {
                $charCount = 77;
                $charNumberOfMessage = $newMessage->getChars();
                $charsLeft = $charCount - $charNumberOfMessage;
                $msg = "| " . $newMessage->getMessage();
                for ($j = 0; $j <= $charsLeft - 2; $j++) {
                    $msg .= " ";
                }
                $msg .= "|";
                $output->writeln($msg);
                $linesPrinted++;
            }

            if ($linesPrinted < 4) {
                for ($j = $linesPrinted; $j < 4; $j++) {
                    $output->writeln("|                                                                             |");
                }
            }
        }
        $output->writeln("+=============================================================================+</>");
    }

    /**
     * @return AdventureLogService
     */
    protected function getAdventureLogService(): AdventureLogService
    {
        return $this->adventureLogService;
    }
}