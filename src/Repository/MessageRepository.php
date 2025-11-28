<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getTchatBetweenUsers($conversation)
    {
        return $this->createQueryBuilder("m")
            ->where("m.conversation = :conversation")
            ->setParameter('conversation',$conversation)
            ->orderBy('m.lastUpdated', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTchatList($ownerId)
    {
        return $this->createQueryBuilder("m")
            ->where("m.owner = :ownerId OR m.userDelivery = :ownerId")
            ->leftJoin('m.owner', 'o')
            ->leftJoin('m.userDelivery', 'u')
            ->setParameter("ownerId", $ownerId)
            ->groupBy('m.conversation')
            ->orderBy('m.lastUpdated', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countNbUnreadByConversation($conversationId, $ownerId)
    {
        return $this->createQueryBuilder("m")
            ->select("count(m.id) as NbUnread")
            ->where("(m.owner = :ownerId AND m.viewOwner = :view) OR (m.userDelivery = :ownerId AND m.viewUserDelivery = :view)")
            ->andWhere("m.conversation = :conversationId")
            ->setParameter('conversationId',$conversationId)
            ->setParameter("ownerId", $ownerId)
            ->setParameter('view',false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
