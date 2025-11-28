<?php

namespace App\Repository;

use App\Entity\UserHasLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserHasLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserHasLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserHasLanguage[]    findAll()
 * @method UserHasLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserHasLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasLanguage::class);
    }

    public function getUserLanguages($userId)
    {
        return $this->createQueryBuilder("uhl")
            ->select("l.id")
            ->leftJoin('uhl.language', 'l')
            ->where("uhl.user = :userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getResult();
    }
}