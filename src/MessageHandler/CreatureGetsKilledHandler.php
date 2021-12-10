<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Level\LevelActionEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureGetsKilledMessage;
use App\Message\PlayerLevelUpMessage;
use App\Model\Loot\Gold;
use App\Service\LoggerService;
use App\Service\PlayerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatureGetsKilledHandler implements MessageHandlerInterface
{
    protected LoggerService $loggerService;
    protected MessageBusInterface $messageBus;
    // TODO rethink PlayerService role in this whole mess.
    // state of reference to an object is pretty complicated here
    // we should aim at stateless or not?
    protected PlayerService $playerService;

    public function __construct(LoggerService $loggerService, MessageBusInterface $messageBus, PlayerService $playerService)
    {
        $this->loggerService = $loggerService;
        $this->messageBus = $messageBus;
        $this->playerService = $playerService;
    }

    public function __invoke(CreatureGetsKilledMessage $message)
    {
        $player = $message->getPlayer();
        $creature = $message->getCreature();

        $goldAmount = 0;
        $inventoryBag = $creature->getLootInventoryBag($player);
        foreach ($inventoryBag->getItems() as $item) {
            if ($item instanceof Gold) {
                $goldAmount += $item->getAmount();
            }
            $player->getInventory()->handleLoot($item);
        }
        $this->messageBus->dispatch(new AddAdventureLogMessage("You've killed ".$creature->getName().' earning 💰 '.$goldAmount.' gold and 🧍 '.$creature->getExperience().' experience points', MessageClassEnum::SUCCESS()));

        // increase level handler
        $initialPlayerLevel = $player->getLevel()->getLevel();
        $player->getLevel()->modifyExperience($creature->getExperience(), LevelActionEnum::INCREASE());
        $currentPlayerLevel = $player->getLevel()->getLevel();
        $player->increaseKillCount();

        // level up handler TODO code duplication, GodModeActivate
        if ($currentPlayerLevel > $initialPlayerLevel) {
            $levelsToGain = $player->getLevel()->getLevel() - $initialPlayerLevel;
            for ($x = 0; $x < $levelsToGain; ++$x) {
                $this->messageBus->dispatch(new PlayerLevelUpMessage($player));
            }
        }

        if ($player->getHealth()->getHealth() <= $player->getHealth()->getWarningThreshold()) {
            $this->messageBus->dispatch(new AddAdventureLogMessage('Your health is low, find a way to heal.', MessageClassEnum::IMPORTANT()));
        }
    }
}
