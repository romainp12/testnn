<?php

namespace App\Repository;

use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    public function getAllSubthemes()
    {
        return $this->createQueryBuilder("t")
            ->select("t.id", "t.name", "i.filePath")
            ->leftJoin('t.image', 'i')
            ->where("t.parent IS NOT NULL")
            ->getQuery()
            ->getArrayResult();
    }

    public function getSubthemes($parentId)
    {
        return $this->createQueryBuilder("t")
            ->select("t.id", "t.name", "i.filePath")
            ->leftJoin('t.image', 'i')
            ->where("t.parent = :parentId")
            ->setParameter("parentId", $parentId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getParentThemes()
    {
        return $this->createQueryBuilder("t")
            ->leftJoin('t.image', 'i')
            ->where("t.parent is NULL")
            ->getQuery()
            ->getResult();
    }

    public function getNbChildThemes($parentId)
    {
        return $this->createQueryBuilder("t")
            ->select("count(t.id) as nbChildThemes")
            ->where("t.parent = :parentId")
            ->setParameter("parentId", $parentId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
