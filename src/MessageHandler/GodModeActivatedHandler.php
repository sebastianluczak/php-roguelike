<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Enum\Misc\AsciiEmoticonEnum;
use App\Enum\Player\Level\LevelActionEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\GodModeActivatedMessage;
use App\Message\PlayerLevelUpMessage;
use App\Service\PlayerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GodModeActivatedHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected PlayerService $playerService;

    public function __construct(MessageBusInterface $messageBus, PlayerService $playerService)
    {
        $this->messageBus = $messageBus;
        $this->playerService = $playerService;
    }

    public function __invoke(GodModeActivatedMessage $message): void
    {
        $player = $message->getPlayer();
        for ($expBase = 100; $expBase <= 500; ++$expBase) {
            $initialPlayerLevel = $player->getLevel()->getLevel();
            $player->getLevel()->modifyExperience((int) ($expBase / sqrt($expBase)), LevelActionEnum::INCREASE());
            $this->messageBus->dispatch(new PlayerLevelUpMessage($player, $initialPlayerLevel));
        }

        $this->messageBus->dispatch(new AddAdventureLogMessage('Have fun! :D '.AsciiEmoticonEnum::FLIP_EM_ALL_TABLES().'.', MessageClassEnum::LOOT()));
    }
}
