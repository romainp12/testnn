<?php

namespace App\Repository;

use App\Entity\UserHasEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserHasEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserHasEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserHasEvent[]    findAll()
 * @method UserHasEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserHasEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasEvent::class);
    }

    public function getView($eventId, $userId)
    {
        return $this->createQueryBuilder("uhe")
            ->select('uhe.view')
            ->where("uhe.event = :eventId")
            ->andWhere("uhe.user = :userId")
            ->setParameter("eventId", $eventId)
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countEventsParticipation($eventId)
    {
        return $this->createQueryBuilder("uhe")
            ->select('COUNT(uhe)')
            ->where("uhe.event = :eventId")
            ->andWhere("uhe.accepted = :accepted")
            ->setParameter("eventId", $eventId)
            ->setParameter("accepted", true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getEventsByType($type, $today, $userId)
    {
        return $this->createQueryBuilder("uhe")
            ->leftJoin('uhe.event', 'e')
            ->where("uhe.user = :userId")
            ->andWhere("e.timeToStart >= :today")
            ->andWhere("e.type = :type")
            ->orderBy('e.createdAt', 'ASC')
            ->setParameter("today", $today)
            ->setParameter("userId", $userId)
            ->setParameter("type", $type)
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function getEventsListsComingByUser($userId, $type)
    {
        $today = new \DateTime("now");

        switch ($type) {
            case "all":
                $res = $this->createQueryBuilder("uhe")
                    ->leftJoin('uhe.event', 'e')
                    ->where("uhe.user = :userId")
                    ->andWhere("e.timeToStart >= :today")
                    ->orderBy('e.timeToStart', 'ASC')
                    ->setParameter("today", $today)
                    ->setParameter("userId", $userId)
                    ->setMaxResults(30)
                    ->getQuery()
                    ->getResult();
                break;
            case "pub":
                $res = $this->getEventsByType(false, $today, $userId);
                break;
            case "priv":
                $res = $this->getEventsByType(true, $today, $userId);
                break;
        }
        return $res;
    }
}
