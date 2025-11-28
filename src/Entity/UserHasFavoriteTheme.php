<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserHasFavoriteThemeRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"userHasFavoriteTheme:read"}},
 *     denormalizationContext={"groups"={"userHasFavoriteTheme:write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={},
 *          "getFavoriteThemeByUserId"={
 *              "method"="GET",
 *              "path"="/user_has_favorite_themes/all/{userId}",
 *              "requirements"={"userId"="\d+"},
 *              "controller"=App\Controller\FavoriteThemeUser::class,
 *              "normalization_context"={"groups"={"FavoriteThemeUser"}}
 *          },
 *
 *     },
 *     itemOperations={
 *         "get"={},
 *         "updateFavoriteTheme"={
 *              "method"="PATCH",
 *              "path"="/user_has_favorite_themes/update/{userId}/{themeId}",
 *              "requirements"={"userId"="\d+", "themeId"="\d+"},
 *              "controller"=App\Controller\FavoriteThemeUpdate::class,
 *              "read"=false,
 *              "validate"=false
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserHasFavoriteThemeRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"user_id", "theme_id"}
 *          )
 *    }
 * )
 */
class UserHasFavoriteTheme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="favoriteThemes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"theme:read", "theme:write", "userHasFavoriteTheme:write", "userHasFavoriteTheme:read", "FavoriteThemeUser"})
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="usersFavorites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read", "user:write", "userHasFavoriteTheme:write", "userHasFavoriteTheme:read", "FavoriteThemeUser"})
     */
    private $theme;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}