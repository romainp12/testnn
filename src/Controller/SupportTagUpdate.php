<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\SupportHasTag;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SupportTagUpdate
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $supportId = $request->attributes->get("supportId");

        $data = json_decode($request->getContent(), true);

        $support = $this->em->getRepository(Support::class)->find($supportId);

        $listTagsExist = $this->em->getRepository(Tag::class)->findAllTagsBySupport($support->getId());

        $ExistingTagsName = [];

        foreach ($listTagsExist as $tagExist) {
            $ExistingTagsName[] = $tagExist["name"];
        }

        foreach ($data["tags"] as $tagData) {
            $dataTagsName[] = $tagData["tag"];
        }

        if (isset($dataTagsName)) {
            $tagsToDelete = array_diff($ExistingTagsName, $dataTagsName);
            $tagsToAdd = array_diff($dataTagsName, $ExistingTagsName);
            if (!empty($tagsToDelete)) {
                $supportHasTagsToDelete = $this->em->getRepository(SupportHasTag::class)->findTagsByName($support->getId(), $tagsToDelete);
                if ($supportHasTagsToDelete) {
                    foreach ($supportHasTagsToDelete as $supportHasTagToDelete) {
                        $this->em->remove($supportHasTagToDelete);
                    }
                    try{
                        $this->em->flush();
                    } catch(\Exception $e){
                        throw new BadRequestHttpException($e);
                    }
                }
            }

            if (!empty($tagsToAdd)) {
                foreach ($tagsToAdd as $tagToAdd) {
                    $tag = $this->em->getRepository(Tag::class)->findOneBy(["name" => $tagToAdd]);
                    if ($tag === NULL) {
                        $tag = new Tag();
                        $tag->setName($tagToAdd);
                    }
                    $supportHasTag = new SupportHasTag();
                    $supportHasTag->setSupport($support);
                    $supportHasTag->setTag($tag);
                    $this->em->persist($tag);
                    $this->em->persist($supportHasTag);
                }
                try{
                    $this->em->flush();
                } catch(\Exception $e){
                    throw new BadRequestHttpException($e);
                }
            }
        } else {
            $supportHasTagsToDelete = $this->em->getRepository(SupportHasTag::class)->findBy(["support" => $support->getId()]);
            if ($supportHasTagsToDelete) {
                foreach ($supportHasTagsToDelete as $supportHasTagToDelete) {
                    $this->em->remove($supportHasTagToDelete);
                }
                try{
                    $this->em->flush();
                } catch(\Exception $e){
                    throw new BadRequestHttpException($e);
                }
            }
        }

        return new Response("OK", Response::HTTP_NO_CONTENT);
    }
}