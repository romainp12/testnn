<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserHasLanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read", "language:read"}},
 *     denormalizationContext={"groups"={"user:write", "language:write"}},
 * )
 * @ORM\Entity(repositoryClass=UserHasLanguageRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"user_id", "language_id"}
 *          )
 *    }
 * )
 */
class UserHasLanguage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="languages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"language:read", "language:write"})
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read", "user:write"})
     */
    private $language;

    /**
     * @ORM\Column(type="smallint")
     */
    private $priority = 0;

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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return UserHasLanguage
     */
    public function setPriority(int $priority): UserHasLanguage
    {
        $this->priority = $priority;
        return $this;
    }
}