<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $stats = [];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function searchUsersByLetters($letters, $userId)
    {
        return $this->createQueryBuilder("u")
            ->select("u.id", "u.name", "i.filePath")
            ->leftJoin('u.image', 'i')
            ->where("u.name LIKE :letters")
            ->andWhere("u.id != :userId")
            ->setParameter('letters','%'. $letters .'%')
            ->setParameter('userId',$userId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getUserByEmail($email)
    {
        return $this->createQueryBuilder("u")
            ->select("u.id", "u.name", "u.roles", "i.filePath")
            ->addSelect("count(s.id) as nbSupportsPublished")
            ->leftJoin('u.image', 'i')
            ->leftJoin('u.supports', 's')
            ->where("u.email = :email")
            ->setParameter('email',$email)
            ->getQuery()
            ->getResult();
    }

    public function getStatsByMultipleCriteria($gender, $years_1, $years_2, $lastMonth)
    {
        if (!$years_1 && $years_2) {
            if ($lastMonth === NULL) {
                $now = new \DateTime();
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate >= :years_2 AND u.endSubscription >= :now')
                    ->setParameter('gender', $gender)
                    ->setParameter('now', $now)
                    ->setParameter('years_2', $years_2)
                    ->getQuery()
                    ->getSingleScalarResult();
            } else {
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate >= :years_2 AND u.createdAt >= :lastMonth')
                    ->setParameter('gender', $gender)
                    ->setParameter('years_2', $years_2)
                    ->setParameter('lastMonth', $lastMonth)
                    ->getQuery()
                    ->getSingleScalarResult();
            }
        } elseif ($years_1 && !$years_2) {
            if ($lastMonth === NULL) {
                $now = new \DateTime();
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate <= :years_1 AND u.endSubscription >= :now')
                    ->setParameter('gender', $gender)
                    ->setParameter('years_1', $years_1)
                    ->setParameter('now', $now)
                    ->getQuery()
                    ->getSingleScalarResult();
            } else {
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate <= :years_1 AND u.createdAt >= :lastMonth')
                    ->setParameter('gender', $gender)
                    ->setParameter('years_1', $years_1)
                    ->setParameter('lastMonth', $lastMonth)
                    ->getQuery()
                    ->getSingleScalarResult();
            }
        } else {
            if ($lastMonth === NULL) {
                $now = new \DateTime();
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate <= :years_1 AND u.birthdate >= :years_2 AND u.endSubscription >= :now')
                    ->setParameter('gender', $gender)
                    ->setParameter('years_1', $years_1)
                    ->setParameter('years_2', $years_2)
                    ->setParameter('now', $now)
                    ->getQuery()
                    ->getSingleScalarResult();
            } else {
                $countRes = $this->createQueryBuilder('u')
                    ->select('COUNT(u)')
                    ->where('u.gender = :gender')
                    ->andWhere('u.birthdate <= :years_1 AND u.birthdate >= :years_2 AND u.createdAt >= :lastMonth')
                    ->setParameter('gender', $gender)
                    ->setParameter('years_1', $years_1)
                    ->setParameter('years_2', $years_2)
                    ->setParameter('lastMonth', $lastMonth)
                    ->getQuery()
                    ->getSingleScalarResult();
            }
        }

        return $countRes;
    }

    public function fillTabStats($keyAgeRange, $years_1, $years_2, $lastMonth)
    {
        $countWoman = $this->getStatsByMultipleCriteria(0, $years_1, $years_2, $lastMonth);
        $countMan = $this->getStatsByMultipleCriteria(1, $years_1, $years_2, $lastMonth);

        $this->stats[$keyAgeRange] = ["Homme" => $countMan, "Femme" => $countWoman];
    }

    public function getUsersByCriteria($lastMonth)
    {
        $last18 = date("Y-m-d", strtotime("-18 years"));
        $years_18 = new \DateTime($last18);
        $this->fillTabStats("ageRange0", null, $years_18, $lastMonth);
        $last25 = date("Y-m-d", strtotime("-25 years"));
        $years_25 = new \DateTime($last25);
        $this->fillTabStats("ageRange1", $years_18, $years_25, $lastMonth);
        $last35 = date("Y-m-d", strtotime("-35 years"));
        $years_35 = new \DateTime($last35);
        $this->fillTabStats("ageRange2", $years_25, $years_35, $lastMonth);
        $last45 = date("Y-m-d", strtotime("-45 years"));
        $years_45 = new \DateTime($last45);
        $this->fillTabStats("ageRange3", $years_35, $years_45, $lastMonth);
        $last55 = date("Y-m-d", strtotime("-55 years"));
        $years_55 = new \DateTime($last55);
        $this->fillTabStats("ageRange4", $years_45, $years_55, $lastMonth);
        $this->fillTabStats("ageRange5", $years_55, null, $lastMonth);

        return $this->stats;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
