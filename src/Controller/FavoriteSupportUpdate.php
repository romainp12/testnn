<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\User;
use App\Entity\UserHasFavoriteSupport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FavoriteSupportUpdate
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");
        $supportId = $request->attributes->get("supportId");

        $res = $this->em->getRepository(UserHasFavoriteSupport::class)->findOneBy(["user" => $userId, "support" => $supportId]);

        if (!$res) {
            $support = $this->em->getRepository(Support::class)->findOneBy(["id" => $supportId]);
            $user = $this->em->getRepository(User::class)->findOneBy(["id" => $userId]);
            $userHasFavSupp = new UserHasFavoriteSupport();
            $userHasFavSupp->setUser($user);
            $userHasFavSupp->setSupport($support);
            $this->em->persist($userHasFavSupp);
        } else {
            $this->em->remove($res);
        }

        try{
            $this->em->flush();
            return new Response("OK", Response::HTTP_NO_CONTENT);
        } catch(\Exception $e){
            throw new BadRequestHttpException($e);
        }
    }
}