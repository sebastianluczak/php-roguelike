<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\GameOverMessage;
use App\Service\LeaderboardService;
use App\Service\PlayerService;
use Carbon\Carbon;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameOverHandler implements MessageHandlerInterface
{
    protected PlayerService $playerService;
    protected LeaderboardService $leaderboardService;
    protected MessageBusInterface $messageBus;

    public function __construct(PlayerService $playerService, LeaderboardService $leaderboardService, MessageBusInterface $messageBus)
    {
        $this->playerService = $playerService;
        $this->leaderboardService = $leaderboardService;
        $this->messageBus = $messageBus;
    }

    public function __invoke(GameOverMessage $message)
    {
        $player = $message->getPlayer();
        try {
            $this->leaderboardService->addEntry($player);
        } catch (\Exception $e) {
            // adventure log offline
        }
        $this->messageBus->dispatch(
            new AddAdventureLogMessage(
            $player->getName().' -> 🗺️ '.$player->getMapLevel().' 🧍 '.$player->getLevel()->getLevel().' ☠️ '.$player->getKillCount().' 💰 '.$player->getGold().' ⏲ '.Carbon::now()->format(DATE_RFC822),
            MessageClassEnum::IMPORTANT()
        )
        );
        $this->messageBus->dispatch(new AddAdventureLogMessage($message->getReason(), MessageClassEnum::IMPORTANT()));
        $this->messageBus->dispatch(new AddAdventureLogMessage(' -- GAME OVER -- ', MessageClassEnum::IMPORTANT()));
    }
}
