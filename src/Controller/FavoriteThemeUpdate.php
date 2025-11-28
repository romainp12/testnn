<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Entity\User;
use App\Entity\UserHasFavoriteTheme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FavoriteThemeUpdate
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");
        $themeId = $request->attributes->get("themeId");

        $res = $this->em->getRepository(UserHasFavoriteTheme::class)->findOneBy(["user" => $userId, "theme" => $themeId]);

        if (!$res) {
            $theme = $this->em->getRepository(Theme::class)->findOneBy(["id" => $themeId]);
            $user = $this->em->getRepository(User::class)->findOneBy(["id" => $userId]);
            $userHasFavTheme = new UserHasFavoriteTheme();
            $userHasFavTheme->setUser($user);
            $userHasFavTheme->setTheme($theme);
            $this->em->persist($userHasFavTheme);
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