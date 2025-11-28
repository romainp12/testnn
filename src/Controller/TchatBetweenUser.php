<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class TchatBetweenUser
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $conversation = $request->attributes->get("conversation");
        $ownerId = $request->attributes->get("ownerId");

        $messages = $this->em->getRepository(Message::class)->getTchatBetweenUsers($conversation);

        foreach ($messages as $message) {
            if ($message->getOwner()->getId() == $ownerId) {
                if ($message->isViewOwner() != true) {
                    $message->setViewOwner(true);
                }
            } elseif ($message->getUserDelivery()->getId() == $ownerId) {
                if ($message->isViewUserDelivery() != true) {
                    $message->setViewUserDelivery(true);
                }
            }
        }

        try{
            $this->em->flush();
            return $messages;
        } catch(\Exception $e){
            throw new BadRequestHttpException($e);
        }
    }
}
