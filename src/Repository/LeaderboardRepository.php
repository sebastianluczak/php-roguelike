<?php

namespace App\Repository;

use App\Entity\Leaderboard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leaderboard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leaderboard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leaderboard[]    findAll()
 * @method Leaderboard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaderboardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leaderboard::class);
    }

    public function getBest(): array
    {
        $query = $this->createQueryBuilder('l')
            ->addOrderBy('l.playerLevel', 'DESC')
            ->addOrderBy('l.kills', 'DESC')
            ->setMaxResults(5)
            ->getQuery();

        // returns an array of Leaderboard objects, sorted
        return $query->getResult();
    }
}
