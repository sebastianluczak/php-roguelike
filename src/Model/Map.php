<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Map\MapInterface;
use App\Model\Tile\AbstractTile;
use App\Model\Tile\EmptyTile;

class Map implements MapInterface
{
    protected string $name;
    protected int $width;
    protected int $height;
    protected array $mapInstance;

    public function __construct(int $width = 140, int $height = 44, string $name = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->name = $name ?? 'Dungeon';
        $this->createMapInstance();
    }

    protected function createMapInstance(): void
    {
        for ($i = 0; $i < $this->height; ++$i) {
            for ($j = 0; $j < $this->width; ++$j) {
                $this->mapInstance[$i][$j] = new EmptyTile();
            }
        }
    }

    public function addTile(AbstractTile $tile, int $width, int $height): void
    {
        $this->mapInstance[$width][$height] = $tile;
    }

    public function getTile(int $width, int $height): AbstractTile
    {
        return $this->mapInstance[$width][$height];
    }

    public function replaceTile(AbstractTile $tile, int $width, int $height): void
    {
        $this->addTile($tile, $width, $height);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getMapInstance(): array
    {
        return $this->mapInstance;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
