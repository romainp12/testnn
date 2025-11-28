<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\UserHasEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventListComing
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");
        $type = $request->attributes->get("type");

        $userHasEvents = $this->em->getRepository(UserHasEvent::class)->getEventsListsComingByUser($userId, $type);

        foreach ($userHasEvents as $userHasEvent) {
            $countParticipation = $this->em->getRepository(UserHasEvent::class)->countEventsParticipation($userHasEvent->getEvent()->getId());
            $userHasEvent->getEvent()->setNbParticipation($countParticipation);
        }

        return $userHasEvents;
    }
}
