<?php

namespace App\Service;

use App\Model\AdventureLog\AdventureLogInterface;
use App\Model\AdventureLog\AdventureLogMessageInterface;
use App\Model\Map;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ConsoleOutputGameService
{
    protected MapService $mapService;
    protected PlayerService $playerService;
    protected DiceService $diceService;
    protected AdventureLogService $adventureLogService;
    protected MessageBusInterface $messageBus;
    protected LeaderboardService $leaderboardService;

    public function __construct(
        MapService $mapService,
        PlayerService $playerService,
        DiceService $diceService,
        AdventureLogService $adventureLogService,
        MessageBusInterface $messageBus,
        LeaderboardService $leaderboardService
    ) {
        $this->mapService = $mapService;
        $this->playerService = $playerService;
        $this->diceService = $diceService;
        $this->adventureLogService = $adventureLogService;
        $this->messageBus = $messageBus;
        $this->leaderboardService = $leaderboardService;
    }

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
            "<fg=blue>--== HEALTH: " . $player->getHealth() .
            " ==-- --== GOLD: " . $player->getGold() .
            "g ==-- --== KILLS: " . $player->getKillCount() .
            " ==-- --== LEVEL: " . $player->getLevel() . " (" . $player->getExperience() % 100 . "/100)" .
            " ==-- --== DAMAGE: " . $player->getDamageScore() .
            " ==-- --== ARMOR: " . $player->getArmorScore() .
            "% ==-- --== DUNGEON DEPTH: " . $this->mapService->getMapLevel() .
            " ==-- --== LOCATION: [" . $player->getCoordinates()->getX() . "][" . $player->getCoordinates()->getY() . "] ==--</>"
        );
    }

    protected function printAdventureLog(AdventureLogInterface $adventureLog, OutputInterface $output)
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

    protected function printGameOverScreen(PlayerInterface $player, OutputInterface $output)
    {
        $output->writeln("SORRY " . $player->getPlayerName() . ", YOU DIED.");
        $output->writeln(" --- LEADERBOARDS --- ");
        $entries = $this->leaderboardService->getEntries();
        foreach ($entries as $entry) {
            $output->writeln("Player: " . $entry->getPlayerName() . " with level: " . $player->getLevel() . " with kills: " . $entry->getKills() . " played at: " . $entry->getCreatedAt()->format(DATE_ISO8601));
        }
    }

    protected function stty($options)
    {
        exec($cmd = "stty $options", $output, $el);
        $el AND die("exec($cmd) failed");

        return implode(" ", $output);
    }

    protected function getPlayerCommand($echo = false): string
    {
        $echo = $echo ? "" : "-echo";
        $stty_settings = preg_replace("#.*; ?#s", "", $this->stty("--all"));
        $this->stty("cbreak $echo");
        $c = fgetc(STDIN);
        $this->stty($stty_settings);

        return $c;
    }

    /**
     * @return AdventureLogService
     */
    protected function getAdventureLogService(): AdventureLogService
    {
        return $this->adventureLogService;
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

    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    /**
     * @return LeaderboardService
     */
    public function getLeaderboardService(): LeaderboardService
    {
        return $this->leaderboardService;
    }
}