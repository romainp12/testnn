<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Event;
use App\Entity\UserHasEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EventItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $_entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Event::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Event
    {
        $nbParticipation = $this->_entityManager->getRepository(UserHasEvent::class)->countEventsParticipation($id);

        $event = $this->_entityManager->getRepository(Event::class)->find($id);

        $event->setNbParticipation($nbParticipation);

        return $event ?? null;
    }
}