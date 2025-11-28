<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\UserHasLanguage;
use App\Entity\UserHasFavoriteTheme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PromotedSupportUser
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $userId = $request->attributes->get("userId");

        $LanguageFav = $this->em->getRepository(UserHasLanguage::class)->getUserLanguages($userId);

        $themesUser = $this->em->getRepository(UserHasFavoriteTheme::class)->getSubThemesByUser($userId);

        return $this->em->getRepository(Support::class)->getPromotedSupports($LanguageFav, $themesUser);
    }
}