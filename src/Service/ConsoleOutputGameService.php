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
    protected InternalClockService $internalClockService;
    protected bool $devMode;

    public function __construct(
        MapService $mapService,
        PlayerService $playerService,
        DiceService $diceService,
        AdventureLogService $adventureLogService,
        MessageBusInterface $messageBus,
        LeaderboardService $leaderboardService,
        InternalClockService $internalClockService
    ) {
        $this->mapService = $mapService;
        $this->playerService = $playerService;
        $this->diceService = $diceService;
        $this->adventureLogService = $adventureLogService;
        $this->messageBus = $messageBus;
        $this->leaderboardService = $leaderboardService;
        $this->internalClockService = $internalClockService;
    }

    /**
     * @return bool
     */
    public function isDevMode(): bool
    {
        return $this->devMode;
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
        $devModeSymbol = "";
        if ($this->devMode) {
            $devModeSymbol = "🦄";
        }

        $output->writeln(
            $devModeSymbol . " <fg=bright-blue>--== 💗 " . $player->getHealth() .
            " ==-- --== 💰 " . $player->getGold() .
            "g ==-- --== ☠️ " . $player->getKillCount() .
            " ==-- --== 🧍 " . $player->getLevel() . " (" . $player->getExperience() % 100 . "/100)" .
            " ==-- --== 🗡️ " . $player->getDamageScore() .
            " ==-- --== 🛡️ " . $player->getArmorScore() .
            "% ==-- --== 🌡️ " . $this->mapService->getMapLevel() .
            " ==-- --== 🗺️ [" . $player->getCoordinates()->getX() . "][" . $player->getCoordinates()->getY() . "] ==--" .
            " --== ⏲ " . str_replace("before", "", $this->internalClockService->getGameStartTime()->diffForHumans(new \DateTime())) .  "</>"
        );
    }

    protected function printAdventureLog(AdventureLogInterface $adventureLog, OutputInterface $output)
    {
        $lines = $adventureLog->getLines();

        $colorHexHashTable = [
            'bright-green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green',
            'green'
        ];

        $output->writeln('<fg=green>');
        $output->writeln("+=+                                                       -=  Adventure Log =-                                                      +=+");
        $output->writeln("+=+=================================================================================================================================+=+</>");

        if ($lines == 0) {
            for ($i = 0; $i < 7; $i++) {
                $output->writeln("");
            }
        } else {
            /** @var AdventureLogMessageInterface $newMessage */
            $linesPrinted = 0;
            foreach ($adventureLog->getNewMessages() as $newMessage) {
                if ($linesPrinted == 0) {
                    $msg = "<fg=$colorHexHashTable[$linesPrinted];options=bold> " . $newMessage->getMessage() . "</>";
                } else {
                    $msg = "<fg=$colorHexHashTable[$linesPrinted]> " . $newMessage->getMessage() . "</>";
                }
                $output->writeln($msg);
                $linesPrinted++;
            }

            if ($linesPrinted <= 7) {
                for ($j = $linesPrinted; $j <= 7; $j++) {
                    $output->writeln("");
                }
            }
        }
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

    /**
     * @return InternalClockService
     */
    public function getInternalClockService(): InternalClockService
    {
        return $this->internalClockService;
    }
}