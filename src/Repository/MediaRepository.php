<?php

namespace App\Repository;

use App\Entity\MediaObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaObject[]    findAll()
 * @method MediaObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaObject::class);
    }

    public function findAllMediasBySupport($supportid)
    {
        return $this->createQueryBuilder("m")
            ->select("m.id")
            ->leftJoin('m.supports', 's')
            ->where("s.support = :supportId")
            ->setParameter("supportId", $supportid)
            ->getQuery()
            ->getResult();
    }
}