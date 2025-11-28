<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Support;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StatUsers
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $lastMonth = new \DateTime("-12 months");

        $tabRes = [];
        $tabUsersRegistered["users registered"] = $this->em->getRepository(User::class)->getUsersByCriteria($lastMonth);
        $tabSupport["supports created"] = $this->em->getRepository(Support::class)->getLastSupportsCreated($lastMonth);
        $tabEvent["events created"] = $this->em->getRepository(Event::class)->getLastEventsCreated($lastMonth);
        $tabUsersSubscribers["users subscribers"] = $this->em->getRepository(User::class)->getUsersByCriteria($lastMonth);
        $tabRes[] = $tabUsersRegistered;
        $tabRes[] = $tabEvent;
        $tabRes[] = $tabSupport;
        $tabRes[] = $tabUsersSubscribers;

        return $tabRes;
    }
}
