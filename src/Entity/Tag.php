<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"name"})
 * @ApiResource(
 *     normalizationContext={"groups"={"tag:read"}},
 *     denormalizationContext={"groups"={"tag:write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={},
 *          "checkExistingTags"={
 *              "method"="GET",
 *              "path"="/tags/exist/{tagNameList}",
 *              "controller"=App\Controller\TagsExist::class,
 *              "validate"=false
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank
     * @Groups({"support:write", "tag:read", "tag:write", "userHasFavoriteSupport:read", "support:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="SupportHasTag", mappedBy="tag", orphanRemoval=true)
     */
    private $supports;

    public function __construct()
    {
        $this->supports = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSupports()
    {
        return $this->supports;
    }

    public function addSupport(SupportHasTag $supportHasTag): self
    {
        if (!$this->supports->contains($supportHasTag)) {
            $this->supports[] = $supportHasTag;
            $supportHasTag->setTag($this);
        }

        return $this;
    }

    public function removeSupport(SupportHasTag $supportHasTag): self
    {
        if ($this->supports->contains($supportHasTag)) {
            $this->supports->removeElement($supportHasTag);
            // set the owning side to null (unless already changed)
            if ($supportHasTag->getTag() === $this) {
                $supportHasTag->setTag(null);
            }
        }

        return $this;
    }
}