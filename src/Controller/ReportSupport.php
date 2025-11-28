<?php

namespace App\Controller;

use App\Entity\Support;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReportSupport
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $supportId = $request->attributes->get("supportId");

        $support = $this->em->getRepository(Support::class)->findOneBy(["id" => $supportId]);

        $support->setReported($support->getReported() + 1);

        try{
            $this->em->flush();
            return new Response("OK", Response::HTTP_NO_CONTENT);
        } catch(\Exception $e){
            throw new BadRequestHttpException($e);
        }
    }
}