<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\UserHasFavoriteTheme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FavoriteThemeUser
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");

        $userHasFavoritesTheme = $this->em->getRepository(UserHasFavoriteTheme::class)->findBy(["user" => $userId]);

        foreach ($userHasFavoritesTheme as $userHasFavTheme) {
            $nbSupportsByTheme = count($this->em->getRepository(Support::class)->findBy(["subTheme" => $userHasFavTheme->getTheme()->getId()]));
            $userHasFavTheme->getTheme()->setNbSupports($nbSupportsByTheme);
        }

        return $userHasFavoritesTheme;
    }
}