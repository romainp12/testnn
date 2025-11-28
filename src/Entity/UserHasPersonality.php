<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserHasPersonalityRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read", "personality:read"}},
 *     denormalizationContext={"groups"={"user:write", "personality:write"}},
 * )
 * @ORM\Entity(repositoryClass=UserHasPersonalityRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="unique",
 *              columns={"user_id", "personality_id"}
 *          )
 *    }
 * )
 */
class UserHasPersonality
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="personalities", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"personality:read", "personality:write"})
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Personality", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read", "user:write"})
     */
    private $personality;

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

    public function getPersonality(): ?Personality
    {
        return $this->personality;
    }

    public function setPersonality(?Personality $personality): self
    {
        $this->personality = $personality;

        return $this;
    }
}
