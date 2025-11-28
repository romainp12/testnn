<?php

namespace App\Controller;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagsExist
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $tagsList = $request->attributes->get("tagNameList");

        $tagsList = explode(",", $tagsList);

        return $this->em->getRepository(Tag::class)->getAllTagsByName($tagsList);
    }
}