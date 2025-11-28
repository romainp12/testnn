<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCheck
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent());
        $email = $content->email;

        $name = $content->name;

        if ($this->em->getRepository(User::class)->findOneBy(["email" => $email])) {
            return new Response("Cette adresse email existe déjà.", Response::HTTP_CONFLICT);
        } elseif ($this->em->getRepository(User::class)->findOneBy(["name" => $name])) {
            return new Response("Ce nom d'utilisateur existe déjà.", Response::HTTP_CONFLICT);
        } else {
            return new Response("OK", Response::HTTP_NO_CONTENT);
        }
    }
}