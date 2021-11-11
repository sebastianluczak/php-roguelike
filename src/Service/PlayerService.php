<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Exception\NewLevelException;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureEncounteredMessage;
use App\Model\Loot\AbstractLoot;
use App\Model\Loot\SkillBoost;
use App\Model\Loot\Weapon\Shield;
use App\Model\Loot\Weapon\Sword;
use App\Model\Player\Player;
use App\Model\Player\PlayerCoordinates;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\ChestTile;
use App\Model\Tile\CorridorTile;
use App\Model\Tile\ExitTile;
use App\Model\Tile\RareChestTile;
use App\Model\Tile\ShopTile;
use Symfony\Component\Messenger\MessageBusInterface;

class PlayerService
{
    protected array $inventory;
    protected int $goldAmount = 0;
    protected int $damageScore = 1;
    protected int $armorScore = 1;
    protected int $health = 100;
    protected int $kills = 0;
    protected int $level = 1;
    protected MessageBusInterface $messageBus;
    protected PlayerInterface $player;
    protected LoggerService $loggerService;

    public function __construct(MessageBusInterface $messageBus, LoggerService $loggerService)
    {
        $this->messageBus = $messageBus;
        $this->loggerService = $loggerService;
        $this->player = new Player("Adventurer", New PlayerCoordinates(0, 0));
    }

    /**
     * @throws NewLevelException
     */
    public function handleTileLogic(AbstractTile $tile, PlayerInterface $player, int $mapLevel)
    {
        $tileLogic = $tile->handleLogic($mapLevel);
        if ($tile->hasLogic()) {
            if ($tile instanceof ChestTile) {
                $this->player->addGoldAmount($tileLogic->getAmount());
                $this->messageBus->dispatch(new AddAdventureLogMessage("You've picked up 💰 " . $tileLogic->getAmount() . "g.", MessageClassEnum::LOOT()));
            }
            if ($tile instanceof RareChestTile) {
                $this->addToInventory($player, $tileLogic);
                if ($tileLogic instanceof Sword) {
                    $this->messageBus->dispatch(new AddAdventureLogMessage("You've picked up 🗡️ " . $tileLogic->getName() . "+" . $tileLogic->getDamage(), MessageClassEnum::LOOT()));
                } elseif ($tileLogic instanceof Shield) {
                    $this->messageBus->dispatch(new AddAdventureLogMessage("You've picked up 🛡️ " . $tileLogic->getName() . "+" . $tileLogic->getArmor(), MessageClassEnum::LOOT()));
                }
            }
            if ($tile instanceof CorridorTile) {
                if ($tileLogic !== null) {
                    // monster fight
                    $this->messageBus->dispatch(new CreatureEncounteredMessage($tileLogic, $player));
                }
            }
            if ($tile instanceof ShopTile) {
                if ($player->getGold() >= 100) {
                    $this->messageBus->dispatch(new AddAdventureLogMessage("🧍 You feel rush of energy after paying some gold to strange man", MessageClassEnum::SUCCESS()));
                    $this->handleSkillBoost($player, $tileLogic);
                    $player->decreaseGoldAmount(100);
                }
            }
            if ($tile instanceof ExitTile) {
                $this->messageBus->dispatch(new AddAdventureLogMessage("You've reached new dungeon level", MessageClassEnum::SUCCESS()));

                throw new NewLevelException();
            }
        }
    }

    public function addToInventory(PlayerInterface $player, AbstractLoot $loot)
    {
        if ($loot->isWeapon()) {
            $player->increaseDamage($loot->getDamage());
        }
        if ($loot->isArmor() && $player->getArmorScore() <= 80) {
            $player->increaseArmor($loot->getArmor());
        }
    }

    /**
     * @param PlayerInterface $player
     * @param SkillBoost $skillBoost
     * @return void
     */
    public function handleSkillBoost(PlayerInterface $player, SkillBoost $skillBoost)
    {
        $player->increaseDamage($skillBoost->getDamageAmount());
        $player->increaseArmor($skillBoost->getArmorAmount());
        $player->increaseHealth($skillBoost->getHealthAmount());
        $player->increaseExperience($skillBoost->getExperience());
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}