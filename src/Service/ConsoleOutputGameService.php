<?php

namespace App\Service;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Model\AdventureLog\AdventureLog;
use App\Model\AdventureLog\AdventureLogInterface;
use App\Model\AdventureLog\AdventureLogMessageInterface;
use App\Model\Map;
use App\Model\Player\PlayerInterface;
use App\Service\Game\StateOfGameService;
use Carbon\Carbon;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ConsoleOutputGameService
{
    protected MapService $mapService;
    protected PlayerService $playerService;
    protected AdventureLogService $adventureLogService;
    protected MessageBusInterface $messageBus;
    protected LeaderboardService $leaderboardService;
    protected InternalClockService $internalClockService;
    protected StateOfGameService $stateOfGameService;

    protected SerializerInterface $serializer;
    protected bool $devMode;

    public function __construct(
        MapService $mapService,
        PlayerService $playerService,
        AdventureLogService $adventureLogService,
        MessageBusInterface $messageBus,
        LeaderboardService $leaderboardService,
        InternalClockService $internalClockService,
        StateOfGameService $stateOfGameService,
        SerializerInterface $serializer
    ) {
        $this->mapService = $mapService;
        $this->playerService = $playerService;
        $this->adventureLogService = $adventureLogService;
        $this->messageBus = $messageBus;
        $this->leaderboardService = $leaderboardService;
        $this->internalClockService = $internalClockService;
        $this->stateOfGameService = $stateOfGameService;
        $this->serializer = $serializer;
    }

    public function isDevMode(): bool
    {
        return $this->devMode ?? (bool) $_ENV['GAME_DEBUG'];
    }

    protected function printMap(Map $map, OutputInterface $output): void
    {
        foreach ($map->getMapInstance() as $column) {
            $line = '';
            foreach ($column as $value) {
                $line .= $value->draw();
            }
            $lines[] = $line;
            $output->writeln($line);
        }
    }

    protected function printPlayerInfo(PlayerInterface $player, OutputInterface $output): void
    {
        $devModeSymbol = '';
        if ($this->isDevMode()) {
            $devModeSymbol = GameIconEnum::DEV_MODE();
        }

        $statusLine = $devModeSymbol.' <fg=bright-blue>'.GameIconEnum::HEALTH().' '.
            $this->formatNumberForPlayerInfo($player->getHealth()->getHealth()).'/'.$this->formatNumberForPlayerInfo($player->getHealth()->getMaxHealth()).
            ' | '.GameIconEnum::POTION().' '.count($player->getInventory()->getInventoryBag()->getItemsOfType(LootTypeEnum::POTION())).
            ' | '.GameIconEnum::GOLD().' '.$this->formatNumberForPlayerInfo($player->getInventory()->getGoldAmount()).
            ' | '.GameIconEnum::KILLS().' '.$player->getKillCount().
            ' | '.GameIconEnum::PLAYER().' '.$player->getLevel()->getLevel().' '.$player->getLevel()->drawExperienceBar().
            ' | '.$player->getInventory()->getWeaponSlot()->getFormattedName().
            ' | '.$player->getInventory()->getArmorSlot()->getFormattedName().
            ' | '.$player->getInventory()->getKeystone()->getFormattedName().
            ' | '.GameIconEnum::WEIGHT().' '.$player->getInventory()->getItemsWeight().'/'.$player->getCarryWeightLimit().
            ' | '.GameIconEnum::MAP().' '.$player->getMapLevel().
            ' | '.GameIconEnum::BUFFS().' '.$this->internalClockService->getActiveGameEventsCount().
            ' | '.GameIconEnum::STATS().' '.$player->getStats()->getFormattedStats().'</>';

        $output->writeln($statusLine);
    }

    protected function formatNumberForPlayerInfo(int $number): string
    {
        if ($number >= 1000) {
            $suffix = 'k';
            $valueAfterDot = $number % 1000;
            $valueBeforeDot = floor($number / 1000);

            return number_format($valueBeforeDot + ($valueAfterDot / 1000), 2).$suffix;
        }

        return number_format($number);
    }

    protected function printAdventureLog(AdventureLogInterface $adventureLog, OutputInterface $output): void
    {
        $terminalWidth = (new Terminal())->getWidth() - 48;

        $ornament = '+=+';
        $title = '-=  Adventure Log  =-';

        $numberOfIntermittentLines = ceil(($terminalWidth - (strlen($ornament) * 2 + strlen($title))) / 2);

        $lines = $adventureLog->getLines();
        $output->writeln('<fg=green>');
        $output->write($ornament);
        for ($i = 0; $i < $numberOfIntermittentLines; ++$i) {
            $output->write(' ');
        }
        $output->write($title);
        for ($i = 0; $i < $numberOfIntermittentLines; ++$i) {
            $output->write(' ');
        }
        $output->write($ornament);
        $output->writeln('');
        $output->write($ornament);
        for ($i = 0; $i < $terminalWidth - (strlen($ornament) * 2); ++$i) {
            $output->write('=');
        }
        $output->writeln($ornament);

        if (0 == $lines) {
            for ($i = 0; $i <= AdventureLog::MAX_NUMBER_OF_MESSAGES; ++$i) {
                $output->writeln('');
            }
        } else {
            $linesPrinted = 0;
            /** @var AdventureLogMessageInterface $newMessage */
            foreach ($adventureLog->getNewMessages() as $newMessage) {
                $output->writeln($newMessage->getMessage());
                ++$linesPrinted;
            }

            if ($linesPrinted <= AdventureLog::MAX_NUMBER_OF_MESSAGES) {
                for ($j = $linesPrinted; $j <= AdventureLog::MAX_NUMBER_OF_MESSAGES; ++$j) {
                    $output->writeln('');
                }
            }
        }
    }

    protected function printLeaderBoards(): void
    {
        $entries = array_reverse($this->leaderboardService->getBestScores());
        $entriesCount = count($entries);
        foreach ($entries as $key => $entry) {
            $this->messageBus->dispatch(
                new AddAdventureLogMessage(
                $entriesCount - $key.'. '.$entry->getPlayerName().' -> '.GameIconEnum::MAP().' '.$entry->getDungeonLevel().' 🧍 '.$entry->getPlayerLevel().' ☠️ '.$entry->getKills().' 💰 '.$entry->getGoldAmount().' ⏲ '.Carbon::createFromImmutable($entry->getCreatedAt())->format(DATE_RFC822),
                MessageClassEnum::IMPORTANT()
            )
            );
        }
        $this->messageBus->dispatch(new AddAdventureLogMessage(' --- Leaderboards --- ', MessageClassEnum::IMPORTANT()));
    }

    protected function stty(string $options): string
    {
        exec($cmd = "stty $options", $output, $el);
        /* @phpstan-ignore-next-line */
        $el and exit("exec($cmd) failed");

        return implode(' ', $output);
    }

    protected function getPlayerCommand(bool $echo = false): string
    {
        $echo = $echo ? '' : '-echo';
        $stty_settings = preg_replace('#.*; ?#s', '', $this->stty('--all'));
        $this->stty("cbreak $echo");
        $c = fgetc(STDIN);
        if ($stty_settings) {
            $this->stty($stty_settings);
        }

        if (is_string($c)) {
            return $c;
        }

        throw new \LogicException('Player command stty failed.');
    }

    protected function getAdventureLogService(): AdventureLogService
    {
        return $this->adventureLogService;
    }

    public function getMapService(): MapService
    {
        return $this->mapService;
    }

    public function getPlayerService(): PlayerService
    {
        return $this->playerService;
    }

    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    public function getLeaderboardService(): LeaderboardService
    {
        return $this->leaderboardService;
    }

    public function getInternalClockService(): InternalClockService
    {
        return $this->internalClockService;
    }
}
