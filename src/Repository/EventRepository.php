<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getEventsList($themes, $userId)
    {
        return $this->createQueryBuilder("e")
            //->leftJoin('e.users', 'uhe')
            ->where("e.type = :public")
            ->andWhere("e.theme IN(:themes)")
            //->andWhere("uhe.user != :userId")
            ->setParameter("public", false)
            //->setParameter("userId", $userId)
            ->setParameter('themes', array_values($themes))
            ->orderBy('e.createdAt', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function getPrivateEventListInvited($userId)
    {
        return $this->createQueryBuilder("e")
            ->leftJoin('e.users', 'uhe')
            ->where("uhe.user = :userId")
            ->andWhere("e.type = :true")
            //->andWhere("uhe.accepted = :false")
            ->orderBy('e.createdAt', 'ASC')
            ->setParameter("userId", $userId)
            ->setParameter("true", true)
            //->setParameter("false", false)
            ->getQuery()
            ->getResult();
    }

    public function getLastEventsCreated($lastMonth)
    {
        return $this->createQueryBuilder("e")
            ->select('COUNT(e)')
            ->where('e.createdAt >= :lastMonth')
            ->setParameter('lastMonth', $lastMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
