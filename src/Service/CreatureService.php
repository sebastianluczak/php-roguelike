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
            '☠️ Encountered '.$creature->getName().' 💗'.$creature->getHealth().'/🗡️'.$creature->getWeaponSlot()->getAverageRoll().'/🛡️'.$creature->getArmorSlot()->getAverageRoll(),
            MessageClassEnum::IMPORTANT()
        ));

        $turn = 1;
        while ($creature->getHealth() >= 0) {
            // todo add critical hits
            // calculate creature hit damage
            $creatureHitDamage = $this->calculateCreatureHitDamage($player, $creature);
            // calculate player damage
            $playerHitDamage = $this->calculatePlayerDamage($creature, $player);

            $this->messageBus->dispatch(
                new AddAdventureLogMessage(
                    '[SCALE:'.$creature->getScale().'] 🗡️ Turn '.$turn.' - '.$creature->getName().' (💗'.$creature->getHealth().'/🗡'.$creatureHitDamage.') vs. '.$player->getName().' (💗'.$player->getHealth()->getHealth().'/🗡'.$playerHitDamage.')',
                    MessageClassEnum::STANDARD()
                )
            );

            $playerInitiative = $player->getInitiative();
            $creatureInitiative = $creature->getInitiative();

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

            ++$turn;
        }

        $this->messageBus->dispatch(new CreatureGetsKilledMessage($creature, $player));
    }

    private function calculateCreatureHitDamage(PlayerInterface $player, CreatureInterface $creature): int
    {
        $playerDamageReduction = DiceBag::factory($player->getInventory()->getArmorSlot()->getDice())->getTotal();
        $creatureDamageRoll = DiceBag::factory($creature->getWeaponSlot()->getDice())->getTotal();

        return (int) (ceil($creatureDamageRoll - $playerDamageReduction) > 0) ? (int) (ceil($creatureDamageRoll - $playerDamageReduction)) : 1;
    }

    private function calculatePlayerDamage(CreatureInterface $creature, PlayerInterface $player): int
    {
        $creatureDamageReduction = ceil(DiceBag::factory($creature->getArmorSlot()->getDice())->getTotal() / 2);
        $playerHitDamageRoll = DiceBag::factory($player->getInventory()->getWeaponSlot()->getDice())->getTotal() + $player->getStats()->getStrength();

        return (int) (ceil($playerHitDamageRoll - $creatureDamageReduction) > 0) ? (int) (ceil($playerHitDamageRoll - $creatureDamageReduction)) : 1;
    }
}
