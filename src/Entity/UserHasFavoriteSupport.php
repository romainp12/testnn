<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserHasFavoriteSupportRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"userHasFavoriteSupport:read"}},
 *     denormalizationContext={"groups"={"userHasFavoriteSupport:write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={},
 *          "getFavoriteSupportsByUserId"={
 *              "method"="GET",
 *              "path"="/user_has_favorite_supports/user/{userId}",
 *              "requirements"={"userId"="\d+"},
 *              "controller"=App\Controller\FavoriteSupportUser::class
 *          }
 *     },
 *     itemOperations={
 *         "get"={},
 *         "updateFavoriteTheme"={
 *              "method"="PATCH",
 *              "path"="/user_has_favorite_supports/update/{userId}/{supportId}",
 *              "requirements"={"userId"="\d+", "supportId"="\d+"},
 *              "controller"=App\Controller\FavoriteSupportUpdate::class,
 *              "read"=false,
 *              "validate"=false
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserHasFavoriteSupportRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"user_id", "support_id"}
 *          )
 *    }
 * )
 */
class UserHasFavoriteSupport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="favoriteSupports", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"support:read", "support:write", "userHasFavoriteSupport:write", "userHasFavoriteSupport:read"})
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="usersFavorites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read", "user:write", "userHasFavoriteSupport:write", "userHasFavoriteSupport:read"})
     */
    private $support;

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

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(?Support $support): self
    {
        $this->support = $support;

        return $this;
    }
}