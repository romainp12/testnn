<?php

namespace App\Controller;

use App\Entity\Support;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SupportTheme
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $themeId = $request->attributes->get("themeId");

        return $this->em->getRepository(Support::class)->getSupportsByThemeIdUser($themeId);
    }
}