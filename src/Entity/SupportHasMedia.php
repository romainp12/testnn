<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SupportHasMediaRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"supportHasMedia:read", "supportHasMedia:read"}},
 *     denormalizationContext={"groups"={"supportHasMedia:write", "supportHasMedia:write"}},
 *     itemOperations={
 *         "get"={},
 *         "updateSupportTag"={
 *              "method"="PATCH",
 *              "path"="/support_has_medias/media/update/{supportId}",
 *              "requirements"={"supportId"="\d+"},
 *              "controller"=App\Controller\SupportMediaUpdate::class,
 *              "read"=false,
 *              "validate"=false
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SupportHasMediaRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"support_id", "media_id"}
 *          )
 *    }
 * )
 */
class SupportHasMedia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="medias", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"media_object:read", "media_object:write"})
     *
     * @Serializer\Expose
     */
    private $support;

    /**
     * @ORM\ManyToOne(targetEntity="MediaObject", inversedBy="supports", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"support:read", "support:write", "media_object:read", "media_object:write", "userHasFavoriteSupport:read", "SearchSupport", "SupportTag", "supportHasMedia:read", "supportHasMedia:write", "SupportByTheme", "StatSupports"})
     */
    private $media;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * @param mixed $support
     * @return SupportHasMedia
     */
    public function setSupport($support)
    {
        $this->support = $support;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     * @return SupportHasMedia
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }
}
