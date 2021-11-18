<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
use App\Exception\GameOverException;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureGetsKilledMessage;
use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use DiceBag\DiceBag;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatureService
{
    protected MessageBusInterface $messageBus;
    protected LoggerService $loggerService;

    public function __construct(MessageBusInterface $messageBus, LoggerService $loggerService)
    {
        $this->messageBus = $messageBus;
        $this->loggerService = $loggerService;
    }

    /**
     * @throws GameOverException
     */
    public function handleFight(CreatureInterface $creature, PlayerInterface $player): void
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage(
            "☠️ Encountered " . $creature->getName() . " 💗" . $creature->getHealth() . "/🗡️" . $creature->getWeaponSlot()->getAverageRoll() . "/🛡️" . $creature->getArmorSlot()->getAverageRoll(),
            MessageClassEnum::IMPORTANT()
        ));

        $turn = 1;
        while ($creature->getHealth() >= 0) {
            if ($turn > 21) {
                // TODO critical issue
                break;
            }

            // check initiative
            // todo initiative rolls
            // todo add critical hits

            // calculate creature hit damage
            $playerDamageReduction = DiceBag::factory($player->getInventory()->getArmorSlot()->getDice())->getTotal();
            $creatureDamageRoll =  DiceBag::factory($creature->getWeaponSlot()->getDice())->getTotal();
            $creatureHitDamage = (ceil($creatureDamageRoll - $playerDamageReduction) > 0)?ceil($creatureDamageRoll - $playerDamageReduction):1;

            // calculate player damage
            $creatureDamageReduction = ceil(DiceBag::factory($creature->getArmorSlot()->getDice())->getTotal() / 2);
            $playerHitDamageRoll = DiceBag::factory($player->getInventory()->getWeaponSlot()->getDice())->getTotal() + $player->getStats()->getStrength();
            $playerHitDamage = (ceil($playerHitDamageRoll - $creatureDamageReduction) > 0)?ceil($playerHitDamageRoll - $creatureDamageReduction):1;
            /*$this->messageBus->dispatch(
                new AddAdventureLogMessage(
                    "CreatureDamageReduction: " . $creatureDamageReduction . ", playerHitDamageRoll: " . $playerHitDamageRoll . ", playerHitDamage: " . $playerHitDamage . " // " .
                    "PlayerDamageReduction: " . $playerDamageReduction . ", creatureDamageRoll: " . $creatureDamageRoll . ", creatureHitDamage: " . $creatureHitDamage,
                    MessageClassEnum::STANDARD()
                )
            );*/
            $this->messageBus->dispatch(
                new AddAdventureLogMessage(
                    "🗡️ Turn " . $turn . " - " . $creature->getName() . " (💗" . $creature->getHealth() . "/🗡".$creatureHitDamage.") vs. " . $player->getPlayerName() . " (💗" . $player->getHealth()->getHealth() . "/🗡".$playerHitDamage.")",
                    MessageClassEnum::STANDARD()
                )
            );

            $playerInitiative = $player->getInitiative();
            $creatureInitiative = $creature->getInitiative();
            // this check doesn't really matter
            if ($playerInitiative < $creatureInitiative) {
                $player->getHealth()->modifyHealth($creatureHitDamage, HealthActionEnum::DECREASE());
                $creature->decreaseHealth($playerHitDamage);
            } else {
                $creature->decreaseHealth($playerHitDamage);
                $player->getHealth()->modifyHealth($creatureHitDamage, HealthActionEnum::DECREASE());
            }

            if ($player->getHealth()->getHealth() <= 0) {
                throw new GameOverException($creature);
            }

            $turn++;
        }

        if ($creature->getHealth() <= 0) {
            // messageBus
            $this->messageBus->dispatch(new CreatureGetsKilledMessage($creature, $player));
        }
    }
}