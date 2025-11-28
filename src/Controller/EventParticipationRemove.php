<?php

namespace App\Controller;

use App\Entity\UserHasEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EventParticipationRemove
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");
        $eventId = $request->attributes->get("eventId");

        $userHasEvent = $this->em->getRepository(UserHasEvent::class)->findOneBy(["user" => $userId, "event" => $eventId]);

        try{
            $this->em->remove($userHasEvent);
            $this->em->flush();
            return new Response("OK", Response::HTTP_NO_CONTENT);
        } catch(\Exception $e){
            throw new BadRequestHttpException($e);
        }
    }
}