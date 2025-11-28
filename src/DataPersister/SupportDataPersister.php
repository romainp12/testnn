<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Support;
use App\Entity\Tag;
use App\Entity\SupportHasTag;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class SupportDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Support;
    }

    /**
     * @param Support $data
     */
    public function persist($data, array $context = [])
    {
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