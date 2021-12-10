<?php

namespace App\Model\Tile\TileLogic;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
use App\Model\Creature\CreatureInterface;
use App\Model\Loot\SkillBoost;
use App\Model\Player\PlayerInterface;
use App\Model\RandomEvent\PleasedTheGodsGameEvent;
use App\Model\RandomEvent\RandomEventInterface;
use App\Model\Stats\StatsInterface;

class ShopTileLogic implements TileLogicInterface
{
    protected SkillBoost $skillBoost;
    protected string $rawMessage;
    protected string $messageClass;
    protected RandomEventInterface $event;

    public function __construct(int $scale, StatsInterface $stats)
    {
        $this->skillBoost = new SkillBoost($scale, $stats);
    }

    public function process(PlayerInterface $player)
    {
        $amountRequired = $player->getLevel()->getLevel() * random_int(
            50 - (int) (sqrt($player->getStats()->getCharisma() + 2)),
            150 - (int) (sqrt($player->getStats()->getCharisma() + 2))
        );

        if ($player->getGold() >= $amountRequired) {
            $this->rawMessage = '🧍 You feel rush of energy after paying '.$amountRequired.' gold to strange man';
            $this->messageClass = MessageClassEnum::SUCCESS();

            $player->getHealth()->modifyHealth($this->skillBoost->getHealthAmount(), HealthActionEnum::INCREASE());
            $player->getStats()->modifyStrength(random_int(0, 1));
            $player->getStats()->modifyPerception(random_int(0, 1));
            $player->getStats()->modifyEndurance(random_int(0, 1));
            $player->getStats()->modifyCharisma(random_int(0, 1));
            $player->getStats()->modifyIntelligence(random_int(0, 1));
            $player->getStats()->modifyAgility(random_int(0, 1));
            $player->getStats()->modifyAgility(random_int(0, 1));

            $player->getInventory()->subtractGoldAmount($amountRequired);
        } else {
            $this->event = new PleasedTheGodsGameEvent($player);
        }
    }

    public function hasAdventureLogMessage(): bool
    {
        return !empty($this->rawMessage);
    }

    public function getAdventureLogMessage(): string
    {
        return $this->rawMessage;
    }

    public function getAdventureLogMessageClass(): string
    {
        return $this->messageClass;
    }

    public function hasEncounter(): bool
    {
        return false;
    }

    public function getEncounteredCreature(): ?CreatureInterface
    {
        return null;
    }

    public function getEvent(): RandomEventInterface
    {
        return $this->event;
    }

    public function hasEvent(): bool
    {
        return !empty($this->event);
    }
}
