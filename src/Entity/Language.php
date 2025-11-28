<?php
// api/src/Entity/Language.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"language:read"}},
 *     denormalizationContext={"groups"={"language:write"}}
 * )
 * @ORM\Entity
 */
class Language
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"language:read", "language:write"})
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"event:read", "language:read", "language:write", "EventListComing", "support:read", "personality:read", "personality:write", "user:read", "FindUser", "EventList"})
     */
    public $name;

    /**
     * @ORM\OneToMany(targetEntity="UserHasLanguage", mappedBy="language")
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
     * @return Language
     */
    public function setName(string $name): Language
    {
        $this->name = $name;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(UserHasLanguage $userHasLanguage): self
    {
        if (!$this->users->contains($userHasLanguage)) {
            $this->users[] = $userHasLanguage;
            $userHasLanguage->setLanguage($this);
        }

        return $this;
    }

    public function removeUser(UserHasLanguage $userHasLanguage): self
    {
        if ($this->users->contains($userHasLanguage)) {
            $this->users->removeElement($userHasLanguage);
            // set the owning side to null (unless already changed)
            if ($userHasLanguage->getLanguage() === $this) {
                $userHasLanguage->setLanguage(null);
            }
        }

        return $this;
    }
}
