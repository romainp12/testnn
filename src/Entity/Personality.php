<?php
// api/src/Entity/Personality.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"personality:read"}},
 *     denormalizationContext={"groups"={"personality:write"}},
 * )
 * @ORM\Entity
 */
class Personality
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name A name property - this description will be available in the API documentation too.
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"personality:read", "personality:write", "user:read"})
     */
    public $name;

    /**
     * @ORM\OneToMany(targetEntity="UserHasPersonality", mappedBy="personality")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Personality
     */
    public function setName(string $name): Personality
    {
        $this->name = $name;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(UserHasPersonality $userHasPersonality): self
    {
        if (!$this->users->contains($userHasPersonality)) {
            $this->users[] = $userHasPersonality;
            $userHasPersonality->setPersonality($this);
        }

        return $this;
    }

    public function removeUser(UserHasPersonality $userHasPersonality): self
    {
        if ($this->users->contains($userHasPersonality)) {
            $this->users->removeElement($userHasPersonality);
            // set the owning side to null (unless already changed)
            if ($userHasPersonality->getPersonality() === $this) {
                $userHasPersonality->setPersonality(null);
            }
        }

        return $this;
    }
}