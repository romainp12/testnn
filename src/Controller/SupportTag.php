<?php

namespace App\Controller;

use App\Entity\SupportHasTag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SupportTag
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $tagId = $request->attributes->get("tagId");

        return $this->em->getRepository(SupportHasTag::class)->findBy(["tag" => $tagId]);
    }
}