<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SupportHasMediaRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"supportHasTag:read"}},
 *     denormalizationContext={"groups"={"supportHasTag:write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={},
 *          "getSupportsByTag"={
 *              "method"="GET",
 *              "path"="/support_has_tags/tag/{tagId}",
 *              "requirements"={"tagId"="\d+"},
 *              "controller"=App\Controller\SupportTag::class,
 *              "normalization_context"={"groups"={"SupportTag"}}
 *          }
 *     },
 *     itemOperations={
 *         "get"={},
 *         "updateSupportTag"={
 *              "method"="PATCH",
 *              "path"="/support_has_tags/tag/update/{supportId}",
 *              "requirements"={"supportId"="\d+"},
 *              "controller"=App\Controller\SupportTagUpdate::class,
 *              "read"=false,
 *              "validate"=false
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SupportHasTagRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"support_id", "tag_id"}
 *          )
 *    }
 * )
 */
class SupportHasTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="tags")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"supportHasTag:read", "supportHasTag:write", "SupportTag"})
     *
     * @Serializer\Expose
     */
    private $support;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="supports", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read"})
     */
    private $tag;

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
     * @return SupportHasTag
     */
    public function setSupport($support)
    {
        $this->support = $support;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     * @return SupportHasTag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }
}