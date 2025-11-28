<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StatThemes
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $themes = $this->em->getRepository(Theme::class)->getAllSubthemes();

        $totalConsulted = 0;
        foreach ($themes as $theme) {
            $nbSupports = $this->em->getRepository(Support::class)->getSupportsByThemeId($theme["id"]);
            foreach ($nbSupports as $support) {
                $totalConsulted += $support["consulted"];
            }
            $theme["nbSupports"] = count($nbSupports);
            $theme["nbConsultations"] = $totalConsulted;
            $themesInfos[] = $theme;
        }

        return $themesInfos;
    }
}
