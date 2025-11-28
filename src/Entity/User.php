<?php
// api/src/Entity/User.php

namespace App\Entity;

use App\Entity\UserHasPersonality;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @UniqueEntity(fields={"email", "name"})
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     itemOperations={
 *          "get"={},
 *          "put"={
 *              "route_name"="api_users_put"
 *          },
 *          "delete"={},
 *          "resetPassword"={
 *              "method"="PATCH",
 *              "path"="/users/resetPassword",
 *              "controller"=App\Controller\Security\ResetPassword::class,
 *              "validate"=false,
 *              "swagger_context"={
 *                  "parameters"={
 *                      {
 *                          "name" = "User",
 *                          "in" = "body",
 *                          "schema" = {
 *                              "type" = "object",
 *                              "properties" = {
 *                                  "password" = {"type"="string"},
 *                                  "email" = {"type"="string"},
 *                              }
 *                           },
 *                          "required" = "true",
 *                      }
 *                  }
 *              },
 *              "read"=false
 *          },
 *          "getUserByEmail"={
 *              "method"="GET",
 *              "path"="/users/getByEmail/{email}",
 *              "controller"=App\Controller\FindUser::class,
 *              "read"=false,
 *              "normalization_context"={"groups"={"FindUser"}}
 *          },
 *          "checkUserByEmailAndName"={
 *              "method"="PATCH",
 *              "path"="/users/exist/check",
 *              "controller"=App\Controller\UserCheck::class,
 *              "validate"=false,
 *              "read"=false
 *          },
 *     },
 *     collectionOperations={
 *          "get"={"maximum_items_per_page"=20},
 *          "post"={
 *          "route_name"="api_users_post"
 *          },
 *          "dashboardStats"={
 *              "method"="GET",
 *              "path"="/users/stats/dashboard",
 *              "controller"=App\Controller\Dashboard::class,
 *              "validate"=false,
 *              "read"=false
 *          },
 *          "usersStats"={
 *              "method"="GET",
 *              "path"="/users/stats/users",
 *              "controller"=App\Controller\StatUsers::class,
 *              "validate"=false,
 *              "read"=false
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name" : "partial"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @Assert\Email(
     *     message = "L'email donné : '{{ value }}' n'est pas un format valide pour un mail."
     * )
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write", "FindUser"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\NotNull(
     *     message = "Le champs name ne peut être nul."
     * )
     * @Groups({"user:read", "user:write", "support:read", "event:read", "userHasFavoriteSupport:read", "message:read", "FavoriteThemeUser", "EventListComing", "FindUser", "TchatList", "TchatBetweenUser", "SupportTag", "SupportByTheme", "EventList"})
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     * @Groups({"FindUser"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(
     *     min=8,
     *     max=20,
     *     minMessage = "Le mot de passe doit comporter un minimum de 8 caractères",
     *     maxMessage = "Le mot de passe doit comporter un maximum de 20 caractères"
     * )
     * @Groups({"user:write"})
     */
    private $temporaryPassword;

    /**
     * @SerializedName("password")
     * @Groups("user:write")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="date")
     * @Groups({"user:read", "user:write", "FindUser"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"user:read", "user:write"})
     */
    private $endSubscription;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "user:write"})
     */
    private $autoSubscription = true;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "user:write"})
     */
    private $gender;

    /**
     * @return int|null
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $idSubscription;

    /**
     * @var MediaObject|null
     *
     * @ORM\OneToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"user:read", "user:write", "support:read", "message:read", "TchatList", "TchatBetweenUser"})
     */
    public $image;

    /**
     * @ORM\OneToMany(targetEntity="UserHasPersonality", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $personalities;

    /**
     * @ORM\OneToMany(targetEntity="UserHasEvent", mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="UserHasFavoriteTheme", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $favoriteThemes;

    /**
     * @ORM\OneToMany(targetEntity="UserHasFavoriteSupport", mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $favoriteSupports;

    /**
     * @ORM\OneToMany(targetEntity="Support", mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $supports;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="owner", cascade={"remove"})
     * @Groups({"user:read", "user:write"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="UserHasLanguage", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user:read", "user:write", "FindUser"})
     */
    private $languages;

    /**
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     * @Groups({"user:read", "user:write"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_default_id", referencedColumnName="id")
     * @Groups({"user:read", "user:write", "FindUser"})
     */
    private $languageDefault;

    /**
     * @Groups({"support:read", "message:read", "FavoriteThemeUser", "message:read", "TchatList", "TchatBetweenUser"})
     */
    private $nbSupportsPublished;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "user:write", "FindUser"})
     */
    private $notificationEnabled = true;

    public function __construct() {
        $this->personalities = new ArrayCollection();
        $this->favoriteThemes = new ArrayCollection();
        $this->favoriteSupports = new ArrayCollection();
        $this->supports = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->endSubscription = new \DateTime("+3 months");
        $this->roles[] = "ROLE_USER";
        $this->roles[] = "ROLE_PRO";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate) : self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSubscription()
    {
        return $this->idSubscription;
    }

    /**
     * @param mixed $idSubscription
     * @return User
     */
    public function setIdSubscription($idSubscription) :self
    {
        $this->idSubscription = $idSubscription;
        return $this;
    }

    public function getPersonalities()
    {
        return $this->personalities;
    }

    public function addPersonality(UserHasPersonality $userHasPersonality): self
    {
        if (!$this->personalities->contains($userHasPersonality)) {
            $this->personalities[] = $userHasPersonality;
            $userHasPersonality->setUser($this);
        }

        return $this;
    }

    public function removePersonality(UserHasPersonality $userHasPersonality): self
    {
        if ($this->personalities->contains($userHasPersonality)) {
            $this->personalities->removeElement($userHasPersonality);
            // set the owning side to null (unless already changed)
            if ($userHasPersonality->getUser() === $this) {
                $userHasPersonality->setUser(null);
            }
        }

        return $this;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function addEvent(UserHasEvent $userHasEvent): self
    {
        if (!$this->events->contains($userHasEvent)) {
            $this->events[] = $userHasEvent;
            $userHasEvent->setUser($this);
        }

        return $this;
    }

    public function removeEvent(UserHasEvent $userHasEvent): self
    {
        if ($this->events->contains($userHasEvent)) {
            $this->events->removeElement($userHasEvent);
            // set the owning side to null (unless already changed)
            if ($userHasEvent->getUser() === $this) {
                $userHasEvent->setUser(null);
            }
        }

        return $this;
    }

    public function getSupports()
    {
        return $this->supports;
    }

    public function addSupport(Support $support): self
    {
        if (!$this->supports->contains($support)) {
            $this->supports[] = $support;
            $support->setUser($this);
        }

        return $this;
    }

    public function removeSupport(Support $support): self
    {
        if ($this->supports->contains($support)) {
            $this->supports->removeElement($support);
            // set the owning side to null (unless already changed)
            if ($support->getUser() === $this) {
                $support->setUser(null);
            }
        }

        return $this;
    }

    public function getMessages()
    {
        return $this->supports;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setOwner($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getOwner() === $this) {
                $message->setOwner(null);
            }
        }

        return $this;
    }

    public function getFavoriteThemes()
    {
        return $this->favoriteThemes;
    }

    public function addFavoriteTheme(UserHasFavoriteTheme $favoriteTheme): self
    {
        if (!$this->favoriteThemes->contains($favoriteTheme)) {
            $this->favoriteThemes[] = $favoriteTheme;
            $favoriteTheme->setUser($this);
        }

        return $this;
    }

    public function removeFavoriteTheme(UserHasFavoriteTheme $favoriteTheme): self
    {
        if ($this->favoriteThemes->contains($favoriteTheme)) {
            $this->favoriteThemes->removeElement($favoriteTheme);
            // set the owning side to null (unless already changed)
            if ($favoriteTheme->getUser() === $this) {
                $favoriteTheme->setUser(null);
            }
        }

        return $this;
    }

    public function getFavoriteSupports()
    {
        return $this->favoriteSupports;
    }

    public function addFavoriteSupport(UserHasFavoriteSupport $favoriteSupport): self
    {
        if (!$this->favoriteSupports->contains($favoriteSupport)) {
            $this->favoriteSupports[] = $favoriteSupport;
            $favoriteSupport->setUser($this);
        }

        return $this;
    }

    public function removeFavoriteSupport(UserHasFavoriteSupport $favoriteSupport): self
    {
        if ($this->favoriteSupports->contains($favoriteSupport)) {
            $this->favoriteSupports->removeElement($favoriteSupport);
            // set the owning side to null (unless already changed)
            if ($favoriteSupport->getUser() === $this) {
                $favoriteSupport->setUser(null);
            }
        }

        return $this;
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
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return User
     */
    public function setImage(?MediaObject $image): User
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function addLanguage(UserHasLanguage $userHasLanguage): self
    {
        if (!$this->languages->contains($userHasLanguage)) {
            $this->languages[] = $userHasLanguage;
            $userHasLanguage->setUser($this);
        }

        return $this;
    }

    public function removeLanguage(UserHasLanguage $userHasLanguage): self
    {
        if ($this->languages->contains($userHasLanguage)) {
            $this->languages->removeElement($userHasLanguage);
            // set the owning side to null (unless already changed)
            if ($userHasLanguage->getUser() === $this) {
                $userHasLanguage->setUser(null);
            }
        }

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
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguageDefault()
    {
        return $this->languageDefault;
    }

    /**
     * @param mixed $languageDefault
     * @return User
     */
    public function setLanguageDefault($languageDefault)
    {
        $this->languageDefault = $languageDefault;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemporaryPassword(): string
    {
        return $this->temporaryPassword;
    }

    /**
     * @param string $temporaryPassword
     * @return User
     */
    public function setTemporaryPassword(string $temporaryPassword): User
    {
        $this->temporaryPassword = $temporaryPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndSubscription()
    {
        return $this->endSubscription;
    }

    /**
     * @param mixed $endSubscription
     * @return User
     */
    public function setEndSubscription($endSubscription)
    {
        $this->endSubscription = $endSubscription;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoSubscription(): bool
    {
        return $this->autoSubscription;
    }

    /**
     * @param bool $autoSubscription
     * @return User
     */
    public function setAutoSubscription(bool $autoSubscription): User
    {
        $this->autoSubscription = $autoSubscription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbSupportsPublished()
    {
        return count($this->supports);
    }

    /**
     * @return bool
     */
    public function isNotificationEnabled(): bool
    {
        return $this->notificationEnabled;
    }

    /**
     * @param bool $notificationEnabled
     * @return User
     */
    public function setNotificationEnabled(bool $notificationEnabled): User
    {
        $this->notificationEnabled = $notificationEnabled;
        return $this;
    }
}
