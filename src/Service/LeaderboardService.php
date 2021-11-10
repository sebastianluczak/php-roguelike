<?php

namespace App\Service;

use App\Entity\Leaderboard;
use App\Model\Player\PlayerInterface;
use App\Repository\LeaderboardRepository;
use DateTime;
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
     * @return array|Leaderboard[]
     */
    public function getEntries(): array
    {
        $query = $this->em->createQuery(
            'SELECT l
            FROM App\Entity\Leaderboard l
            ORDER BY l.playerLevel DESC'
        );

        // todo move to repository
        // returns an array of Leaderboard objects
        return $query->getResult();
    }

    public function addEntry(PlayerInterface $player)
    {
        $leaderboard = new Leaderboard();
        $leaderboard->setCreatedAt(\DateTimeImmutable::createFromMutable(new DateTime()));
        $leaderboard->setDungeonLevel(1);
        $leaderboard->setGoldAmount($player->getGold());
        $leaderboard->setKills($player->getKillCount());
        $leaderboard->setPlayerLevel($player->getLevel());
        $leaderboard->setPlayerName($player->getPlayerName());

        $this->em->persist($leaderboard);
        $this->em->flush();
    }
}