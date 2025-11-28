<?php

namespace App\Controller;

use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ParentThemes
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $themes = $this->em->getRepository(Theme::class)->getParentThemes();

        foreach ($themes as $theme) {
            $res = $this->em->getRepository(Theme::class)->getNbChildThemes($theme->getId());
            $theme->setNbChildThemes($res);
        }

        return $themes;
    }
}