<?php

namespace App\DataPersister;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class MessagePersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Message;
    }

    /**
     * @param Message $data
     */
    public function persist($data, array $context = [])
    {
        if (($context['collection_operation_name'] ?? null) === 'post') {
            $conversation_exist = $data->getUserDelivery()->getId() . "_" . $data->getOwner()->getId();
            if ($this->_entityManager->getRepository(Message::class)->findOneBy(["conversation" => $conversation_exist])) {
                $data->setConversation($conversation_exist);
            } else {
                $data->setConversation($data->getOwner()->getId() . "_" . $data->getUserDelivery()->getId());
            }
        }

        if (($context['collection_operation_name'] ?? null) === 'put') {
            $data->setLastUpdated(new \DateTime("now"));
        }

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}