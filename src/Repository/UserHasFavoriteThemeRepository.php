<?php

namespace App\Repository;

use App\Entity\UserHasFavoriteTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserHasFavoriteTheme|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserHasFavoriteTheme|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserHasFavoriteTheme[]    findAll()
 * @method UserHasFavoriteTheme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserHasFavoriteThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserhasFavoriteTheme::class);
    }

    public function getIdThemesByUser($userId)
    {
        return $this->createQueryBuilder("uft")
            ->select("t.id")
            ->leftJoin("uft.theme", "t")
            ->where("uft.user =:userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getThemesByUser($userId)
    {
        return $this->createQueryBuilder("uft")
            ->join("uft.theme", "t")
            ->where("uft.user =:userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getResult();
    }

    public function getSubThemesByUser($userId)
    {
        return $this->createQueryBuilder("uft")
            ->select("t.id")
            ->join("uft.theme", "t")
            ->where("uft.user =:userId")
            ->andWhere("t.parent IS NOT NULL")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getResult();
    }
}
