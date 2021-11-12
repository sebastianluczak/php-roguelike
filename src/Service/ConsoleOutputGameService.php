<?php

namespace App\Service;

use App\Entity\Leaderboard;
use App\Enum\GameIconEnum;
use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Model\AdventureLog\AdventureLog;
use App\Model\AdventureLog\AdventureLogInterface;
use App\Model\AdventureLog\AdventureLogMessageInterface;
use App\Model\Map;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use Carbon\Carbon;
use DateTime;
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
        return $this->devMode??(bool)$_ENV['GAME_DEBUG'];
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
        if ($this->isDevMode()) {
            $devModeSymbol = GameIconEnum::DEV_MODE();
        }

        $output->writeln(
            $devModeSymbol . " <fg=bright-blue>--== " . GameIconEnum::HEALTH() . " " . $player->getHealth() .
            " ==-- --== " . GameIconEnum::GOLD() . " " . $player->getGold() .
            "g ==-- --== " . GameIconEnum::KILLS() . " " . $player->getKillCount() .
            " ==-- --== " . GameIconEnum::PLAYER() . " " . $player->getLevel() . " (" . $player->getExperience() % 100 . "/100)" .
            " ==-- --== " . GameIconEnum::WEAPON() . " " . $player->getDamageScore() .
            " ==-- --== " . GameIconEnum::SHIELD() . " " . $player->getArmorScore() .
            "% ==-- --== " . GameIconEnum::MAP() . " " . $player->getMapLevel() .
            " ==-- --== " . GameIconEnum::BUFFS() . " " . $this->internalClockService->getActiveGameEventsCount() ." ==--" .
            " --== " . GameIconEnum::STATS() . " " . $player->getStats()->getFormattedStats() .  " ==--</>"
        );
    }

    protected function printAdventureLog(AdventureLogInterface $adventureLog, OutputInterface $output)
    {
        $lines = $adventureLog->getLines();
        $output->writeln('<fg=green>');
        $output->writeln("+=+                                                       -=  Adventure Log =-                                                      +=+");
        $output->writeln("+=+=================================================================================================================================+=+</>");

        if ($lines == 0) {
            for ($i = 0; $i <= AdventureLog::MAX_NUMBER_OF_MESSAGES; $i++) {
                $output->writeln("");
            }
        } else {
            /** @var AdventureLogMessageInterface $newMessage */
            $linesPrinted = 0;
            foreach ($adventureLog->getNewMessages() as $newMessage) {
                $output->writeln($newMessage->getMessage());
                $linesPrinted++;
            }

            if ($linesPrinted <= AdventureLog::MAX_NUMBER_OF_MESSAGES) {
                for ($j = $linesPrinted; $j <= AdventureLog::MAX_NUMBER_OF_MESSAGES; $j++) {
                    $output->writeln("");
                }
            }
        }
    }

    protected function printLeaderBoards(PlayerInterface $player)
    {
        $entries = array_reverse($this->leaderboardService->getBestScores());
        /**
         * @var int $key
         * @var Leaderboard $entry
         */
        $entriesCount = count($entries);
        foreach ($entries as $key => $entry) {
            $this->messageBus->dispatch(new AddAdventureLogMessage(
                $entriesCount - $key . ". " . $entry->getPlayerName() . " -> " . GameIconEnum::MAP() . " " . $entry->getDungeonLevel() . " 🧍 " . $entry->getPlayerLevel() . " ☠️ " . $entry->getKills() . " 💰 " . $entry->getGoldAmount() . " ⏲ " . Carbon::createFromImmutable($entry->getCreatedAt())->format(DATE_RFC822), MessageClassEnum::IMPORTANT())
            );
        }
        $this->messageBus->dispatch(new AddAdventureLogMessage(" --- Leaderboards --- ", MessageClassEnum::IMPORTANT()));
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