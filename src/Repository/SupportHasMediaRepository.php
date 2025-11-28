<?php

namespace App\Repository;

use App\Entity\SupportHasMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupportHasMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupportHasMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupportHasMedia[]    findAll()
 * @method SupportHasMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupportHasMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupportHasMedia::class);
    }

    public function findMediasById($supportid, $mediasList)
    {
        return $this->createQueryBuilder("shm")
            ->leftJoin('shm.media', 'm')
            ->where("shm.support = :supportId")
            ->andWhere("m.id IN(:mediasList)")
            ->setParameter("supportId", $supportid)
            ->setParameter("mediasList", $mediasList)
            ->getQuery()
            ->getResult();
    }
}
