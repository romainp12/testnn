<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"message:read"}},
 *     denormalizationContext={"groups"={"message:write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={},
 *          "getTchatBetweenUser"={
 *              "attributes"={"pagination_client_items_per_page"=true},
 *              "method"="GET",
 *              "path"="/messages/tchat/conversation/{conversation}/{ownerId}/{page}",
 *              "requirements"={"ownerId"="\d+", "page"="\d+"},
 *              "controller"=App\Controller\TchatBetweenUser::class,
 *              "normalization_context"={"groups"={"TchatBetweenUser"}},
 *              "maximum_items_per_page"=100
 *          },
 *          "getTchatList"={
 *              "method"="GET",
 *              "path"="/messages/tchat/list/{ownerId}",
 *              "requirements"={"ownerId"="\d+"},
 *              "controller"=App\Controller\TchatList::class,
 *              "normalization_context"={"groups"={"TchatList"}}
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="text", length=2500)
     * @Assert\NotBlank
     * @Groups({"message:read", "message:write", "TchatList", "TchatBetweenUser"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @Groups({"message:read", "message:write", "TchatList", "TchatBetweenUser"})
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Groups({"message:read", "message:write", "TchatList", "TchatBetweenUser"})
     */
    private $userDelivery;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="messages")
     * @Groups({"message:read", "message:write"})
     */
    private $event;

    /**
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Groups({"message:read", "message:write", "TchatBetweenUser"})
     */
    private $lastUpdated;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message:read", "message:write", "TchatList"})
     */
    private $viewOwner = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message:read", "message:write", "TchatList"})
     */
    private $viewUserDelivery = false;

    /**
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     * @Groups({"message:read", "TchatList"})
     */
    private $conversation;

    /**
     * @var integer
     * @Groups({"message:read", "TchatList"})
     */
    private $nbUnread = 0;

    public function __construct()
    {
        $this->lastUpdated = new \DateTime();
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
     * @return Message
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     * @return Message
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserDelivery()
    {
        return $this->userDelivery;
    }

    /**
     * @param mixed $userDelivery
     * @return Message
     */
    public function setUserDelivery($userDelivery)
    {
        $this->userDelivery = $userDelivery;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     * @return Message
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdated(): \DateTime
    {
        return $this->lastUpdated;
    }

    /**
     * @param \DateTime $lastUpdated
     * @return Message
     */
    public function setLastUpdated(\DateTime $lastUpdated): Message
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * @param mixed $conversation
     * @return Message
     */
    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbUnread(): int
    {
        return $this->nbUnread;
    }

    /**
     * @param int $nbUnread
     * @return Message
     */
    public function setNbUnread(int $nbUnread): Message
    {
        $this->nbUnread = $nbUnread;
        return $this;
    }

    /**
     * @return bool
     */
    public function isViewOwner(): bool
    {
        return $this->viewOwner;
    }

    /**
     * @param bool $viewOwner
     * @return Message
     */
    public function setViewOwner(bool $viewOwner): Message
    {
        $this->viewOwner = $viewOwner;
        return $this;
    }

    /**
     * @return bool
     */
    public function isViewUserDelivery(): bool
    {
        return $this->viewUserDelivery;
    }

    /**
     * @param bool $viewUserDelivery
     * @return Message
     */
    public function setViewUserDelivery(bool $viewUserDelivery): Message
    {
        $this->viewUserDelivery = $viewUserDelivery;
        return $this;
    }
}
