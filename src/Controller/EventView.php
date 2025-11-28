<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\UserHasEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EventView
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

        $userHasEvent = $this->em->getRepository(UserHasEvent::class)->findOneBy(["event" => $eventId, "user" => $userId]);

        $userHasEvent->setView(true);

        try{
            $this->em->flush();
            return new Response("OK", Response::HTTP_NO_CONTENT);
        } catch(\Exception $e){
            throw new BadRequestHttpException($e);
        }
    }
}