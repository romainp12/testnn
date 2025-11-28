<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\Support;
use App\Entity\SupportHasMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SupportMediaUpdate
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

        $listMediasExist = $this->em->getRepository(MediaObject::class)->findAllMediasBySupport($support->getId());

        $ExistingMedia = [];

        foreach ($listMediasExist as $mediaExist) {
            $ExistingMedia[] = $mediaExist["id"];
        }

        $tabMedia = $data["medias"]["media"];

        if (!empty($tabMedia)) {
            $mediasToDelete = array_diff($ExistingMedia, $tabMedia);
            $mediasToAdd = array_diff($tabMedia, $ExistingMedia);
            if (!empty($mediasToDelete)) {
                $supportHasMediasToDelete = $this->em->getRepository(SupportHasMedia::class)->findMediasById($support->getId(), $mediasToDelete);
                if ($supportHasMediasToDelete) {
                    foreach ($supportHasMediasToDelete as $supportHasMediaToDelete) {
                        $this->em->remove($supportHasMediaToDelete);
                    }
                    try{
                        $this->em->flush();
                    } catch(\Exception $e){
                        throw new BadRequestHttpException($e);
                    }
                }
            }

            if (!empty($mediasToAdd)) {
                foreach ($mediasToAdd as $mediaToAdd) {
                    $mediaObject = $this->em->getRepository(MediaObject::class)->findOneBy(["id" => $mediaToAdd]);
                    $supportHasMedia = new SupportHasMedia();
                    $supportHasMedia->setSupport($support);
                    $supportHasMedia->setMedia($mediaObject);
                    $this->em->persist($supportHasMedia);
                }
                try{
                    $this->em->flush();
                } catch(\Exception $e){
                    throw new BadRequestHttpException($e);
                }
            }
        } else {
            $supportHasMediasToDelete = $this->em->getRepository(SupportHasMedia::class)->findBy(["support" => $support->getId()]);
            if ($supportHasMediasToDelete) {
                foreach ($supportHasMediasToDelete as $supportHasMediaToDelete) {
                    $this->em->remove($supportHasMediaToDelete);
                }
                try{
                    $this->em->flush();
                } catch(\Exception $e){
                    throw new BadRequestHttpException($e);
                }
            }
        }

        return new Response("OK", Response::HTTP_NO_CONTENT);

        /*$listTagsExist = $this->em->getRepository(Tag::class)->findAllTagsBySupport($support->getId());

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

        return new Response("OK", Response::HTTP_NO_CONTENT);*/
    }
}