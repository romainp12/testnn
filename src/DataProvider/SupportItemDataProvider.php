<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Support;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SupportItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $_entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Support::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Support
    {
        $support = $this->_entityManager->getRepository(Support::class)->find($id);

        $support->getConsulted($support->getConsulted() + 1);

        return $support ?? null;
    }
}
