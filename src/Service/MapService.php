<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Enum\MoveDirectionEnum;
use App\Exception\MapFiniteException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Generator\Level\DefaultBoxRoomGenerator;
use App\Helper\ScaleHelper;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureEncounteredMessage;
use App\Message\TileInteractionMessage;
use App\Message\TileLogicMessage;
use App\Model\CityMap;
use App\Model\Creature\CreatureInterface;
use App\Model\Map;
use App\Model\Player\PlayerCoordinates;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\AltarTile;
use App\Model\Tile\BossRoomTile;
use App\Model\Tile\ChestTile;
use App\Model\Tile\City\PavementTile;
use App\Model\Tile\CorridorTile;
use App\Model\Tile\EmptyTile;
use App\Model\Tile\ExitTile;
use App\Model\Tile\RareChestTile;
use App\Model\Tile\SpawnTile;
use App\Model\Tile\StrangeManTile;
use App\Traits\Tile\PermanentTileTrait;
use Exception;
use Irfa\Gatcha\Roll;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MapService
{
    protected Map $map;
    protected CityMap $cityMap;
    protected Map $tempDungeonMap;

    protected ValidatorInterface $validator;
    protected PlayerService $playerService;
    protected LoggerService $loggerService;
    protected int $mapLevel = 1;
    protected MessageBusInterface $messageBus;
    protected YamlLevelParserService $levelParserService;
    protected array $mapErrors;
    private array $tilesAvailableToSpawnWithChances = [
        BossRoomTile::class => 1, // change to 100 to have some flashback to '98 and Minesweeper :P
        StrangeManTile::class => 8,
        AltarTile::class => 12,
        RareChestTile::class => 20,
        ChestTile::class => 20,
        CorridorTile::class => 6000,
    ];

    public function __construct(PlayerService $playerService, LoggerService $loggerService, MessageBusInterface $messageBus, YamlLevelParserService $levelParserService)
    {
        $this->playerService = $playerService;
        $this->loggerService = $loggerService;
        $this->messageBus = $messageBus;
        $this->levelParserService = $levelParserService;
        $this->mapErrors = [];
        $this->createNewLevel();
        $this->generateCityMapInstance();
    }

    public function createNewLevel(): void
    {
        $this->map = new Map((new Terminal())->getWidth() - 48, (new Terminal())->getHeight() - 18);
        $this->generateMap();

        $mapValid = $this->isMapValid();
        if (!$mapValid) {
            $this->messageBus->dispatch(new AddAdventureLogMessage('Map regenerated due to errors: '.implode(', ', $this->mapErrors), MessageClassEnum::DEVELOPER()));
            $this->createNewLevel();
        } else {
            $this->mapErrors = [];
            $this->playerService->getPlayer()->setMapLevel($this->mapLevel);
        }
    }

    public function generateMap(): void
    {
        $spawnTileCoordinates = [random_int(0, $this->map->getHeight()), random_int(0, $this->map->getWidth())];
        // spawn tile
        $this->map->addTile(new SpawnTile(), $spawnTileCoordinates[0], $spawnTileCoordinates[1]);
        $this->playerService->getPlayer()->setCoordinates(new PlayerCoordinates($spawnTileCoordinates[0], $spawnTileCoordinates[1]));

        try {
            $this->generateMaze($spawnTileCoordinates);
        } catch (Exception $e) {
        }
    }

    public function generateDevRoomMap(): void
    {
        $this->map = new Map();

        $spawnTileCoordinates = [0, 0];
        $this->map->addTile(new SpawnTile(), $spawnTileCoordinates[0], $spawnTileCoordinates[1]);
        //$this->playerService->getPlayer()->setCoordinates(new PlayerCoordinates($spawnTileCoordinates[0], $spawnTileCoordinates[1]));

        for ($j = 1; $j <= 30; ++$j) {
            for ($i = 0; $i <= 50; ++$i) {
                $this->map->addTile(new RareChestTile(), $j, $i);
            }
        }
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    protected function generateMaze(array $spawnTileCoordinates): void
    {
        $currentCoordinates = $spawnTileCoordinates;
        // look for next available coordinates
        // first, get random direction
        try {
            $nextMapTileCoordinates = $this->getNextPossibleTile($currentCoordinates);
        } catch (MapFiniteException $e) {
            $this->addExitTileToMap($currentCoordinates);

            throw new MapFiniteException();
        }
        $this->map->addTile($this->createRandomTile(), $nextMapTileCoordinates[0], $nextMapTileCoordinates[1]);
        $this->generateMaze($nextMapTileCoordinates);
    }

    /**
     * @throws Exception
     */
    protected function getNextPossibleTile(array $currentCoordinates, int $tries = 0): array
    {
        ++$tries;
        if ($tries > 8) {
            throw new MapFiniteException();
        }
        $direction = random_int(0, 3); // UP, RIGHT, DOWN, LEFT
        switch ($direction) {
            case 0: // UP
                if ($currentCoordinates[0] - 1 >= 0) {
                    if ($this->isTileEmpty($currentCoordinates[0] - 1, $currentCoordinates[1])) {
                        $currentCoordinates[0] = $currentCoordinates[0] - 1;
                    } else {
                        $this->getNextPossibleTile($currentCoordinates, $tries);
                    }
                } else {
                    $this->getNextPossibleTile($currentCoordinates, $tries);
                }
                break;
            case 1: // RIGHT
                if ($currentCoordinates[1] + 1 < $this->map->getWidth()) {
                    if ($this->isTileEmpty($currentCoordinates[0], $currentCoordinates[1] + 1)) {
                        $currentCoordinates[1] = $currentCoordinates[1] + 1;
                    } else {
                        $this->getNextPossibleTile($currentCoordinates, $tries);
                    }
                } else {
                    $this->getNextPossibleTile($currentCoordinates, $tries);
                }
                break;
            case 2: // DOWN
                if ($currentCoordinates[0] + 1 < $this->map->getHeight()) {
                    if ($this->isTileEmpty($currentCoordinates[0] + 1, $currentCoordinates[1])) {
                        $currentCoordinates[0] = $currentCoordinates[0] + 1;
                    } else {
                        $this->getNextPossibleTile($currentCoordinates, $tries);
                    }
                } else {
                    $this->getNextPossibleTile($currentCoordinates, $tries);
                }
                break;
            case 3: // LEFT
                if ($currentCoordinates[1] - 1 >= 0) {
                    if ($this->isTileEmpty($currentCoordinates[0], $currentCoordinates[1] - 1)) {
                        $currentCoordinates[1] = $currentCoordinates[1] - 1;
                    } else {
                        $this->getNextPossibleTile($currentCoordinates, $tries);
                    }
                } else {
                    $this->getNextPossibleTile($currentCoordinates, $tries);
                }
                break;
        }

        return $currentCoordinates;
    }

    private function isTileEmpty(int $width, int $height): bool
    {
        $tile = $this->map->getTile($width, $height);
        if ($tile instanceof EmptyTile || $tile instanceof CorridorTile || $tile instanceof ChestTile) {
            return true;
        }

        return false;
    }

    /**
     * @throws NotValidMoveException
     * @throws NewLevelException
     */
    public function movePlayer(PlayerInterface $player, string $direction, int $mapLevel): void
    {
        $this->loggerService->log('Moving player: '.$player->getName().' in direction: '.$direction);
        $xcoordinate = null;
        $ycoordinate = null;

        // todo change to player instance
        // This is a big change to be fair, whole logic is based on SpawnTile
        // We should discuss option to somehow inject spawnTile into Player (PlayerTileRepresentation ?)
        // and be sure it's coordinates changes or catch all occurences (better option) of SpawnTile and
        // replace them with $player->getPlayerTileRepresention(): PlayerTileRepresentation extends SpawnTile (to rename)
        // should work but crazy amount of work, 2h?
        $spawnTile = $this->getSpawnTile();
        // check if valid move
        switch ($direction) {
            case MoveDirectionEnum::UP():
                if ($this->isTileTraversable($spawnTile[0] - 1, $spawnTile[1])) {
                    $xcoordinate = $spawnTile[0] - 1;
                    $ycoordinate = $spawnTile[1];
                }
                break;
            case MoveDirectionEnum::DOWN():
                if ($this->isTileTraversable($spawnTile[0] + 1, $spawnTile[1])) {
                    $xcoordinate = $spawnTile[0] + 1;
                    $ycoordinate = $spawnTile[1];
                }
                break;
            case MoveDirectionEnum::LEFT():
                if ($this->isTileTraversable($spawnTile[0], $spawnTile[1] - 1)) {
                    $xcoordinate = $spawnTile[0];
                    $ycoordinate = $spawnTile[1] - 1;
                }
                break;
            case MoveDirectionEnum::RIGHT():
                if ($this->isTileTraversable($spawnTile[0], $spawnTile[1] + 1)) {
                    $xcoordinate = $spawnTile[0];
                    $ycoordinate = $spawnTile[1] + 1;
                }
                break;
        }

        if (null === $xcoordinate || null === $ycoordinate) {
            $this->loggerService->log('Not valid move');

            throw new NotValidMoveException('Not valid move');
        } else {
            $player->setCoordinates(new PlayerCoordinates($xcoordinate, $ycoordinate));
        }

        // check logic of tile
        $tile = $this->getMap()->getTile($xcoordinate, $ycoordinate);
        if ($tile->hasLogic()) {
            $this->handleTileLogic($tile, $player, $mapLevel);
        }

        if ($tile->isInteractable()) {
            $this->handleTileInteraction($tile, $player);
        }

        // remove player from previous tile
        if ($this->getMap() instanceof CityMap) {
            if (in_array(PermanentTileTrait::class, array_keys((new \ReflectionClass(get_class($tile)))->getTraits()))) {
                while (true) {
                    // todo sometimes this fails in smaller rooms
                    // as it's PoC i'll leave it as it is
                    // yeah, it's simple collision detection
                    // nice piece of work to be fair
                    // (and I should've used cursor from the beginning...)
                    $xCoordinateRandom = random_int(-1, 1);
                    $yCoordinateRandom = random_int(-1, 1);
                    if ($this->getCityMap()->getTile($xcoordinate - $xCoordinateRandom, $ycoordinate - $yCoordinateRandom)->isPassable()) {
                        $this->getMap()->replaceTile($tile, $xcoordinate - $xCoordinateRandom, $ycoordinate - $yCoordinateRandom);

                        break;
                    }
                }
            }
            $this->getMap()->replaceTile(new PavementTile(), $spawnTile[0], $spawnTile[1]);
        } else {
            $this->getMap()->replaceTile(new CorridorTile(), $spawnTile[0], $spawnTile[1]);
        }

        // add player to new tile
        $this->getMap()->addTile(new SpawnTile(), $xcoordinate, $ycoordinate);
    }

    protected function getSpawnTile(): array
    {
        foreach ($this->getMap()->getMapInstance() as $xposition => $row) {
            foreach ($row as $yposition => $tile) {
                if ($tile instanceof SpawnTile) {
                    return [$xposition, $yposition];
                }
            }
        }

        return [0, 0];
    }

    public function getCityMap(): CityMap
    {
        return $this->cityMap;
    }

    protected function generateCityMapInstance(): void
    {
        $yamlFilePath = 'resources/levels/Rivermouth_city.yaml';
        $this->levelParserService->setYamlFilePath($yamlFilePath);
        $cityMapScaffold = new CityMap(
            $this->levelParserService->getMapWidth(),
            $this->levelParserService->getMapHeight(),
            $this->levelParserService->getMapName()
        );

        $rooms = $this->levelParserService->getRoomsDefinitions();
        foreach ($rooms as $roomDefinition) {
            /** @var DefaultBoxRoomGenerator $generator */
            $generator = new $roomDefinition['generation_class']($cityMapScaffold, $roomDefinition);
            $cityMapScaffold = $generator->getMap();
        }
        $cityMapScaffold->addTile(new SpawnTile(), 1, 1);

        $this->cityMap = $cityMapScaffold;
    }

    private function isTileTraversable(int $width, int $height): bool
    {
        /* @var AbstractTile $tile */
        if (!isset($this->getMap()->getMapInstance()[$width][$height])) {
            return false;
        }
        $tile = $this->getMap()->getMapInstance()[$width][$height];
        if ($tile->isPassable()) {
            return true;
        }

        return false;
    }

    private function createRandomTile(): AbstractTile
    {
        $tileRolled = Roll::put($this->tilesAvailableToSpawnWithChances)->spin();

        $tileToAdd = new $tileRolled();

        if ($tileToAdd instanceof AbstractTile) {
            return $tileToAdd;
        }

        throw new \LogicException('Tile should always extend AbstractTile, in: '.__METHOD__);
    }

    public function increaseMapLevel(): void
    {
        $this->mapLevel = $this->mapLevel + 1;
    }

    private function addExitTileToMap(array $coordinates): void
    {
        $this->getMap()->replaceTile(new ExitTile(), $coordinates[0], $coordinates[1]);
    }

    public function getMapLevel(): int
    {
        return $this->mapLevel;
    }

    private function isMapValid(): bool
    {
        $this->mapErrors = [];
        $mapTileStatistics = [];
        $allTiles = 0;
        foreach ($this->getMap()->getMapInstance() as $arrayOfTiles) {
            foreach ($arrayOfTiles as $mapTile) {
                $mapTileStatistics[get_class($mapTile)] = (isset($mapTileStatistics[get_class($mapTile)])) ? $mapTileStatistics[get_class($mapTile)] + 1 : 1;
                ++$allTiles;
            }
        }
        $mapTileStatistics['count'] = $allTiles;

        try {
            $percentageOfEmptyTiles = round($mapTileStatistics[EmptyTile::class] / $allTiles * 100, 2);
            $numberOfSpawnTiles = $mapTileStatistics[SpawnTile::class];
        } catch (Exception $e) {
            $this->mapErrors[] = 'Mandatory tiles not found';

            return false;
        }

        if ($percentageOfEmptyTiles > 88) {
            $this->mapErrors[] = 'Playable area too small: '.$percentageOfEmptyTiles.'%';
        }

        if (1 != $numberOfSpawnTiles) {
            $this->mapErrors[] = 'Wrong SpawnTile count: '.$numberOfSpawnTiles.', expecting 1';
        }

        if (0 == count($this->mapErrors)) {
            return true;
        }

        return false;
    }

    /**
     * @throws NewLevelException
     */
    public function handleTileLogic(AbstractTile $tile, PlayerInterface $player, int $mapLevel): void
    {
        $scale = ScaleHelper::basicScale($mapLevel, $player->getLevel()->getLevel());
        $tileLogic = $tile->handleLogic($scale, $player->getStats());

        if ($tile->hasLogic()) {
            $this->messageBus->dispatch(new TileLogicMessage($player, $tileLogic));

            if ($tileLogic->hasEncounter()) {
                if ($tileLogic->getEncounteredCreature() instanceof CreatureInterface) {
                    $this->messageBus->dispatch(new CreatureEncounteredMessage($tileLogic->getEncounteredCreature(), $player));
                }
            }

            if ($tile instanceof ExitTile) {
                $this->messageBus->dispatch(new AddAdventureLogMessage("You've reached new dungeon level", MessageClassEnum::SUCCESS()));

                throw new NewLevelException();
            }
        }
    }

    /**
     * @throws NewLevelException
     */
    public function handleTileInteraction(AbstractTile $tile, PlayerInterface $player): void
    {
        $tileInteraction = $tile->handleInteraction($player);

        if ($tile->isInteractable()) {
            $this->messageBus->dispatch(new TileInteractionMessage($player, $tileInteraction));
        }
    }

    public function resetErrors(): void
    {
        $this->mapErrors = [];
    }

    public function getTempDungeonMap(): Map
    {
        return $this->tempDungeonMap;
    }

    public function setTempDungeonMap(Map $tempDungeonMap): void
    {
        $this->tempDungeonMap = $tempDungeonMap;
    }

    public function setMap(Map $map): MapService
    {
        $this->map = $map;

        return $this;
    }
}
