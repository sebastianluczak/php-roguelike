<?php

namespace App\Entity;

use App\Repository\LeaderboardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeaderboardRepository::class)
 */
class Leaderboard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $playerName;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $dungeonLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldAmount;

    /**
     * @ORM\Column(type="integer")
     */
    private $kills;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): self
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getPlayerLevel(): ?int
    {
        return $this->playerLevel;
    }

    public function setPlayerLevel(int $playerLevel): self
    {
        $this->playerLevel = $playerLevel;

        return $this;
    }

    public function getDungeonLevel(): ?int
    {
        return $this->dungeonLevel;
    }

    public function setDungeonLevel(int $dungeonLevel): self
    {
        $this->dungeonLevel = $dungeonLevel;

        return $this;
    }

    public function getGoldAmount(): ?int
    {
        return $this->goldAmount;
    }

    public function setGoldAmount(int $goldAmount): self
    {
        $this->goldAmount = $goldAmount;

        return $this;
    }

    public function getKills(): ?int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
