<?php

namespace App\Service;

use App\Entity\Leaderboard;
use App\Model\Player\PlayerInterface;
use App\Repository\LeaderboardRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class LeaderboardService
{
    protected LeaderboardRepository $repository;
    protected EntityManagerInterface $em;

    public function __construct(LeaderboardRepository $leaderboardRepository, EntityManagerInterface $em)
    {
        $this->repository = $leaderboardRepository;
        $this->em = $em;
    }

    /**
     * @return Leaderboard[]|array
     */
    public function getBestScores(): array
    {
        try {
            $records = $this->repository->getBest();
        } catch (\Exception $e) {
            $records = [];
        }

        return $records;
    }

    public function addEntry(PlayerInterface $player): void
    {
        $leaderboard = new Leaderboard();
        $leaderboard->setCreatedAt(DateTimeImmutable::createFromMutable(new DateTime()));
        $leaderboard->setDungeonLevel($player->getMapLevel());
        $leaderboard->setGoldAmount($player->getInventory()->getGoldAmount());
        $leaderboard->setKills($player->getKillCount());
        $leaderboard->setPlayerLevel($player->getLevel()->getLevel());
        $leaderboard->setPlayerName($player->getName());

        $this->em->persist($leaderboard);
        $this->em->flush();
    }
}
