<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TchatList
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $ownerId = $request->attributes->get("ownerId");

        $conversation = $this->em->getRepository(Message::class)->getTchatList($ownerId);

        foreach ($conversation as $conv) {
            $nbUnread = $this->em->getRepository(Message::class)->countNbUnreadByConversation($conv->getConversation(), $ownerId);
            $conv->setNbUnread($nbUnread);
        }

        return $conversation;
    }
}