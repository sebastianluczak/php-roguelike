<?php

namespace App\Service;

use App\Enum\MoveDirectionEnum;
use App\Exception\MapFiniteException;
use App\Exception\NotValidMoveException;
use App\Model\Map;
use App\Model\Player\PlayerCoordinates;
use App\Model\Player\PlayerInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\ChestTile;
use App\Model\Tile\CorridorTile;
use App\Model\Tile\EmptyTile;
use App\Model\Tile\ExitTile;
use App\Model\Tile\RareChestTile;
use App\Model\Tile\ShopTile;
use App\Model\Tile\SpawnTile;

class MapService
{
    protected ?Map $map = null;
    protected PlayerService $playerService;
    protected LoggerService $loggerService;
    protected int $mapLevel = 1;

    public function __construct(PlayerService $playerService, LoggerService $loggerService)
    {
        $this->playerService = $playerService;
        $this->loggerService = $loggerService;

        if ($this->map == null) {
            $this->createNewLevel();
        }
    }

    public function createNewLevel(): void
    {
        $this->map = new Map();
        $this->generateMap();
    }

    public function generateMap(): void
    {
        $spawnTileCoordinates = [random_int(0, $this->map->getHeight()), random_int(0, $this->map->getWidth())];
        // spawn tile
        $this->map->addTile(new SpawnTile(), $spawnTileCoordinates[0], $spawnTileCoordinates[1]);
        $this->playerService->getPlayer()->setCoordinates(new PlayerCoordinates($spawnTileCoordinates[0], $spawnTileCoordinates[1]));

        try {
            $this->generateMaze($spawnTileCoordinates);
        } catch (\Exception $e) {
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
        }
        $this->map->addTile($this->createRandomTile(), $nextMapTileCoordinates[0], $nextMapTileCoordinates[1]);
        $this->generateMaze($nextMapTileCoordinates);
    }

    /**
     * @throws \Exception
     */
    protected function getNextPossibleTile(array $currentCoordinates, int $tries = 0): array
    {
        $tries++;
        if ($tries > 6) {
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
     * @throws \App\Exception\NewLevelException
     */
    public function movePlayer(PlayerInterface $player, string $direction, int $mapLevel)
    {
        $this->loggerService->log("Moving player: " . $player->getPlayerName() . " in direction: " . $direction);
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
            $this->playerService->handleTileLogic($tile, $player, $mapLevel);
        }
        // remove player from previous tile
        $this->getMap()->replaceTile(new CorridorTile(), $spawnTile[0], $spawnTile[1]);
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

    private function isTileTraversable(int $width, int $height): bool
    {
        $this->loggerService->log("IsTileTraversable @" . $width . "#" . $height);
        /** @var AbstractTile $tile */
        if (!isset($this->getMap()->getMapInstance()[$width][$height])) {
            return false;
        }
        $tile = $this->getMap()->getMapInstance()[$width][$height];
        $this->loggerService->log("Tile: " . get_class($tile));
        $this->loggerService->log("TileIsPassable : " . $tile->isPassable());
        if ($tile->isPassable()) {
            return true;
        }

        return false;
    }

    private function createRandomTile(): AbstractTile
    {
        $roll = random_int(0, 1000);

        if ($roll <= 1) {
            return new ShopTile();
        } else if ($roll <= 5) {
            return new RareChestTile();
        } else if ($roll <= 10) {
            return new ChestTile();
        }

        return new CorridorTile();
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
}