<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubThemes
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $parentId = $request->attributes->get("parentId");

        $themes = $this->em->getRepository(Theme::class)->getSubthemes($parentId);

        foreach ($themes as $theme) {
            $nbSupports = $this->em->getRepository(Support::class)->getSupportsByThemeId($theme["id"]);
            $theme["nbSupports"] = count($nbSupports);
            $themesInfos[] = $theme;
        }

        return $themesInfos;
    }
}
