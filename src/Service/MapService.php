<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
use App\Enum\MoveDirectionEnum;
use App\Exception\MapFiniteException;
use App\Exception\NewLevelException;
use App\Exception\NotValidMoveException;
use App\Generator\Level\DefaultBoxRoomGenerator;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureEncounteredMessage;
use App\Message\TileInteractionMessage;
use App\Message\TileLogicMessage;
use App\Model\CityMap;
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
use App\Model\Tile\ShopTile;
use App\Model\Tile\SpawnTile;
use Exception;
use Irfa\Gatcha\Roll;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MapService
{
    protected ?Map $map = null;
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
        BossRoomTile::class => 1,
        ShopTile::class => 8,
        AltarTile::class => 12,
        RareChestTile::class => 20,
        ChestTile::class => 20,
        CorridorTile::class => 6000
    ];

    public function __construct(PlayerService $playerService, LoggerService $loggerService, MessageBusInterface $messageBus, YamlLevelParserService $levelParserService)
    {
        $this->playerService = $playerService;
        $this->loggerService = $loggerService;
        $this->messageBus = $messageBus;
        $this->levelParserService = $levelParserService;
        $this->mapErrors = [];

        if ($this->map == null) {
            $this->createNewLevel();
        }

        $this->generateCityMapInstance();
    }

    public function createNewLevel(): void
    {
        $this->map = new Map((new Terminal())->getWidth() - 48, (new Terminal())->getHeight() - 18);
        $this->generateMap();

        $mapValid = $this->isMapValid();
        if (!$mapValid) {
            $this->messageBus->dispatch(new AddAdventureLogMessage("Map regenerated due to errors: " . implode(", ", $this->mapErrors), MessageClassEnum::DEVELOPER()));
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

    public function generateDevRoomMap()
    {
        $this->map = new Map();

        $spawnTileCoordinates = [0, 0];
        $this->map->addTile(new SpawnTile(), $spawnTileCoordinates[0], $spawnTileCoordinates[1]);
        //$this->playerService->getPlayer()->setCoordinates(new PlayerCoordinates($spawnTileCoordinates[0], $spawnTileCoordinates[1]));

        for ($j=1;$j<=30;$j++) {
            for ($i = 0; $i <= 50; $i++) {
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
        $tries++;
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

    private function isTileEmpty(int $width, int $height)
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
    public function movePlayer(PlayerInterface $player, string $direction, int $mapLevel)
    {
        $this->loggerService->log("Moving player: " . $player->getName() . " in direction: " . $direction);
        $xcoordinate = null;
        $ycoordinate = null;

        // todo change to player instance
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

        $this->loggerService->log("Move coordinates: " . $xcoordinate . " / " . $ycoordinate);
        if ($xcoordinate === null || $ycoordinate === null) {
            $this->loggerService->log("Not valid move");

            throw new NotValidMoveException("Not valid move");
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
            $this->getMap()->replaceTile(new PavementTile(), $spawnTile[0], $spawnTile[1]);
        } else {
            $this->getMap()->replaceTile(new CorridorTile(), $spawnTile[0], $spawnTile[1]);
        }

        // add player to new tile
        $this->getMap()->addTile(new SpawnTile(), $xcoordinate, $ycoordinate);
    }

    protected function getSpawnTile()
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

    /**
     * @return CityMap
     */
    public function getCityMap(): CityMap
    {
        return $this->cityMap;
    }

    protected function generateCityMapInstance()
    {
        $yamlFilePath = "resources/levels/Rivermouth_city.yaml";
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
        /** @var AbstractTile $tile */
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

        return new $tileRolled();
    }

    public function increaseMapLevel()
    {
        $this->mapLevel = $this->mapLevel + 1;
    }

    private function addExitTileToMap(array $coordinates)
    {
        $this->getMap()->replaceTile(new ExitTile(), $coordinates[0], $coordinates[1]);
    }

    /**
     * @return int
     */
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
                $allTiles++;
            }
        }
        $mapTileStatistics["count"] = $allTiles;

        try {
            $percentageOfEmptyTiles = round($mapTileStatistics[EmptyTile::class] / $allTiles * 100, 2);
            $numberOfSpawnTiles = $mapTileStatistics[SpawnTile::class];
        } catch (Exception $e) {
            $this->mapErrors[] = "Mandatory tiles not found";

            return false;
        }

        if ($percentageOfEmptyTiles > 88) {
            $this->mapErrors[] = "Playable area too small: " . $percentageOfEmptyTiles . "%";
        }

        if ($numberOfSpawnTiles != 1) {
            $this->mapErrors[] = "Wrong SpawnTile count: " . $numberOfSpawnTiles . ", expecting 1";
        }

        if (count($this->mapErrors) == 0) {
            return true;
        }

        return false;
    }

    /**
     * @throws NewLevelException
     */
    public function handleTileLogic(AbstractTile $tile, PlayerInterface $player, int $mapLevel)
    {
        $scale = $mapLevel + $player->getLevel()->getLevel();

        $tileLogic = $tile->handleLogic($scale, $player->getStats());

        if ($tile->hasLogic()) {
            $this->messageBus->dispatch(new TileLogicMessage($player, $tileLogic));

            if ($tileLogic->hasEncounter()) {
                $this->messageBus->dispatch(new CreatureEncounteredMessage($tileLogic->getEncounteredCreature(), $player));
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
    public function handleTileInteraction(AbstractTile $tile, PlayerInterface $player)
    {
        $tileInteraction = $tile->handleInteraction($player);

        if ($tile->isInteractable()) {
            $this->messageBus->dispatch(new TileInteractionMessage($player, $tileInteraction));
        }
    }

    public function resetErrors()
    {
        $this->mapErrors = [];
    }

    /**
     * @return Map
     */
    public function getTempDungeonMap(): Map
    {
        return $this->tempDungeonMap;
    }

    /**
     * @param Map $tempDungeonMap
     */
    public function setTempDungeonMap(Map $tempDungeonMap): void
    {
        $this->tempDungeonMap = $tempDungeonMap;
    }

    /**
     * @param Map|null $map
     * @return MapService
     */
    public function setMap(?Map $map): MapService
    {
        $this->map = $map;
        return $this;
    }
}
