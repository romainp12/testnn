<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"theme:read"}},
 *     denormalizationContext={"groups"={"theme:write"}},
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={"AllThemes"}}},
 *          "post"={},
 *          "getSubThemes"={
 *              "method"="GET",
 *              "path"="/themes/subthemes/{parentId}",
 *              "requirements"={"parentId"="\d+"},
 *              "controller"=App\Controller\SubThemes::class
 *          },
 *          "getParentThemes"={
 *              "method"="GET",
 *              "path"="/themes/parent",
 *              "controller"=App\Controller\ParentThemes::class,
 *              "normalization_context"={"groups"={"ParentThemes"}}
 *          },
 *          "themesStats"={
 *              "method"="GET",
 *              "path"="/themes/stats",
 *              "controller"=App\Controller\StatThemes::class,
 *              "validate"=false,
 *              "read"=false
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ThemeRepository")
 */
class Theme
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull
     * @Groups({"theme:read", "theme:write", "user:read", "support:read", "event:read", "userHasFavoriteTheme:read", "FavoriteThemeUser", "ParentThemes", "EventList", "AllThemes"})
     */
    public $name;

    /**
     * @ORM\ManyToOne(targetEntity="Theme")
     * @Groups({"theme:read", "theme:write", "userHasFavoriteTheme:read", "FavoriteThemeUser", "AllThemes"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="UserHasFavoriteTheme", mappedBy="theme", cascade={"remove"})
     * @Groups({"theme:read", "theme:write"})
     */
    private $usersFavorites;

    /**
     * @var MediaObject|null
     *
     * @ORM\OneToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"theme:read", "theme:write", "FavoriteThemeUser", "ParentThemes", "AllThemes"})
     */
    public $image;

    /**
     * @Groups({"FavoriteThemeUser"})
     */
    private $nbSupports;

    /**
     * @Groups({"ParentThemes"})
     */
    private $nbChildThemes = 0;

    public function __construct()
    {
        $this->usersFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Theme
     */
    public function setName(string $name): Theme
    {
        $this->name = $name;
        return $this;
    }

    public function getUsersFavorites()
    {
        return $this->usersFavorites;
    }

    public function addUserFavorite(UserHasFavoriteTheme $userHasFavoriteTheme): self
    {
        if (!$this->usersFavorites->contains($userHasFavoriteTheme)) {
            $this->usersFavorites[] = $userHasFavoriteTheme;
            $userHasFavoriteTheme->setTheme($this);
        }

        return $this;
    }

    public function removeUserFavorite(UserHasFavoriteTheme $userHasFavoriteTheme): self
    {
        if ($this->usersFavorites->contains($userHasFavoriteTheme)) {
            $this->usersFavorites->removeElement($userHasFavoriteTheme);
            // set the owning side to null (unless already changed)
            if ($userHasFavoriteTheme->getTheme() === $this) {
                $userHasFavoriteTheme->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return Theme
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    /**
     * @param MediaObject|null $image
     * @return Theme
     */
    public function setImage(?MediaObject $image): Theme
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbSupports()
    {
        return $this->nbSupports;
    }

    /**
     * @param mixed $nbSupports
     * @return Theme
     */
    public function setNbSupports($nbSupports)
    {
        $this->nbSupports = $nbSupports;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbChildThemes(): int
    {
        return $this->nbChildThemes;
    }

    /**
     * @param int $nbChildThemes
     * @return Theme
     */
    public function setNbChildThemes(int $nbChildThemes): Theme
    {
        $this->nbChildThemes = $nbChildThemes;
        return $this;
    }
}
