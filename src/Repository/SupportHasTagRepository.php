<?php

namespace App\Repository;

use App\Entity\SupportHasTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupportHasTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupportHasTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupportHasTag[]    findAll()
 * @method SupportHasTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupportHasTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupportHasTag::class);
    }

    public function findTagsByName($supportid, $tagsList)
    {
        return $this->createQueryBuilder("sht")
            ->leftJoin('sht.tag', 't')
            ->where("sht.support = :supportId")
            ->andWhere("t.name IN(:tagsList)")
            ->setParameter("supportId", $supportid)
            ->setParameter("tagsList", $tagsList)
            ->getQuery()
            ->getResult();
    }
}