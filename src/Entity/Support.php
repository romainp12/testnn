<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"support:read"}},
 *     denormalizationContext={"groups"={"support:write"}},
 *     attributes={
 *          "pagination_items_per_page"=6
 *     },
 *     collectionOperations={
 *          "get"={},
 *          "post"={"security"="is_granted('ROLE_PRO')"},
 *          "getSupportsByUser"={
 *              "method"="GET",
 *              "path"="/supports/user/{userId}",
 *              "requirements"={"userId"="\d+"},
 *              "controller"=App\Controller\SupportUser::class
 *          },
 *          "getPromotedSupportsByUser"={
 *              "method"="GET",
 *              "path"="/supports/promoted/user/{userId}",
 *              "requirements"={"userId"="\d+"},
 *              "controller"=App\Controller\PromotedSupportUser::class
 *          },
 *          "getSupportsByTheme"={
 *              "method"="GET",
 *              "path"="/supports/theme/{themeId}",
 *              "requirements"={"themeId"="\d+"},
 *              "controller"=App\Controller\SupportTheme::class,
 *              "read"=false,
 *              "normalization_context"={"groups"={"SupportByTheme"}}
 *          },
 *          "increaseReported"={
 *              "method"="PATCH",
 *              "path"="/supports/report/increase/{supportId}",
 *              "requirements"={"supportId"="\d+"},
 *              "controller"=App\Controller\ReportSupport::class,
 *              "validate"=false
 *          },
 *          "supportsStats"={
 *              "method"="GET",
 *              "path"="/supports/stats",
 *              "controller"=App\Controller\StatSupports::class,
 *              "validate"=false,
 *              "read"=false,
 *              "normalization_context"={"groups"={"StatSupports"}}
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"title" : "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\SupportRepository")
 */
class Support
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"SupportByTheme"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Theme")
     * @ORM\JoinColumn(name="subtheme_id", referencedColumnName="id", nullable=false)
     * @Groups({"support:read", "support:write"})
     */
    private $subTheme;

    /**
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read", "SearchSupport", "SupportTag", "SupportByTheme", "StatSupports"})
     */
    private $title;

    /**
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups({"support:read", "support:write", "SearchSupport", "SupportTag", "SupportByTheme"})
     */
    private $subtitle;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Groups({"support:read", "support:write", "SearchSupport", "SupportTag", "SupportByTheme"})
     */
    private $type;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"support:read", "support:write", "SearchSupport", "SupportTag", "SupportByTheme"})
     */
    private $type2;

    /**
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     * @Groups({"support:read", "support:write"})
     */
    private $videoLink;

    /**
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     * @Groups({"support:read", "support:write"})
     */
    private $videoLink2;

    /**
     *
     * @ORM\Column(type="text", length=2500)
     * @Assert\NotBlank
     * @Groups({"support:read", "support:write"})
     */
    private $description;

    /**
     *
     * @ORM\Column(type="text", length=2500, nullable=true)
     * @Groups({"support:read", "support:write"})
     */
    private $description2;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="supports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"support:read", "support:write", "SupportTag", "SupportByTheme"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="UserHasFavoriteSupport", mappedBy="support", cascade={"remove"})
     */
    private $usersFavorites;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @Groups({"support:read", "support:write"})
     */
    private $language;

    /**
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read", "SupportTag", "SupportByTheme"})
     */
    private $createdAt;

    /**
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Groups({"support:read", "support:write", "SupportByTheme"})
     */
    private $lastUpdated;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read", "SupportTag", "SupportByTheme"})
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="SupportHasMedia", mappedBy="support", cascade={"persist", "remove"})
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read", "SearchSupport", "SupportTag", "SupportByTheme", "StatSupports"})
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="SupportHasTag", mappedBy="support", cascade={"persist", "remove"})
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:read"})
     */
    private $tags;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"StatSupports"})
     */
    private $reported = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"StatSupports"})
     */
    private $consulted = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->lastUpdated = new \DateTime();
        $this->usersFavorites = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Support
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Support
     */
    public function setTitle(string $title): Support
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     * @return Support
     */
    public function setSubtitle(string $subtitle): Support
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Support
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Support
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUsersFavorites()
    {
        return $this->usersFavorites;
    }

    public function addUserFavorite(UserHasFavoriteSupport $userHasFavoriteSupport): self
    {
        if (!$this->usersFavorites->contains($userHasFavoriteSupport)) {
            $this->usersFavorites[] = $userHasFavoriteSupport;
            $userHasFavoriteSupport->setSupport($this);
        }

        return $this;
    }

    public function removeUserFavorite(UserHasFavoriteSupport $userHasFavoriteSupport): self
    {
        if ($this->usersFavorites->contains($userHasFavoriteSupport)) {
            $this->usersFavorites->removeElement($userHasFavoriteSupport);
            // set the owning side to null (unless already changed)
            if ($userHasFavoriteSupport->getSupport() === $this) {
                $userHasFavoriteSupport->setSupport(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return Support
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Support
     */
    public function setCreatedAt(\DateTime $createdAt): Support
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     * @return Support
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubTheme()
    {
        return $this->subTheme;
    }

    /**
     * @param mixed $subTheme
     * @return Support
     */
    public function setSubTheme($subTheme)
    {
        $this->subTheme = $subTheme;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * @param mixed $videoLink
     * @return Support
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoLink2()
    {
        return $this->videoLink2;
    }

    /**
     * @param mixed $videoLink2
     * @return Support
     */
    public function setVideoLink2($videoLink2)
    {
        $this->videoLink2 = $videoLink2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * @param mixed $description2
     * @return Support
     */
    public function setDescription2($description2)
    {
        $this->description2 = $description2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @param mixed $lastUpdated
     * @return Support
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType2()
    {
        return $this->type2;
    }

    /**
     * @param mixed $type2
     * @return Support
     */
    public function setType2($type2)
    {
        $this->type2 = $type2;
        return $this;
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function addMedia(SupportHasMedia $supportHasMedia): self
    {
        if (!$this->medias->contains($supportHasMedia)) {
            $this->medias[] = $supportHasMedia;
            $supportHasMedia->setSupport($this);
        }

        return $this;
    }

    public function removeMedia(SupportHasMedia $supportHasMedia): self
    {
        if ($this->medias->contains($supportHasMedia)) {
            $this->medias->removeElement($supportHasMedia);
            // set the owning side to null (unless already changed)
            if ($supportHasMedia->getSupport() === $this) {
                $supportHasMedia->setSupport(null);
            }
        }

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(SupportHasTag $supportHasTag): self
    {
        if (!$this->tags->contains($supportHasTag)) {
            $this->tags[] = $supportHasTag;
            $supportHasTag->setSupport($this);
        }

        return $this;
    }

    public function removeTag(SupportHasTag $supportHasTag): self
    {
        if ($this->tags->contains($supportHasTag)) {
            $this->tags->removeElement($supportHasTag);
            // set the owning side to null (unless already changed)
            if ($supportHasTag->getSupport() === $this) {
                $supportHasTag->setSupport(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getReported(): int
    {
        return $this->reported;
    }

    /**
     * @param int $reported
     * @return Support
     */
    public function setReported(int $reported): Support
    {
        $this->reported = $reported;
        return $this;
    }

    /**
     * @return int
     */
    public function getConsulted(): int
    {
        return $this->consulted;
    }

    /**
     * @param int $consulted
     * @return Support
     */
    public function setConsulted(int $consulted): Support
    {
        $this->consulted = $consulted;
        return $this;
    }
}
