<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UserController extends Controller
{
    private $_entityManager;
    private $_passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route(
     *     name="api_users_post",
     *     path="/users",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="post"
     *     }
     * )
     */
    public function postAction(User $data): User
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->_passwordEncoder->encodePassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
        }

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();

        return $data;
    }

    /**
     * @Route(
     *     name="api_users_put",
     *     path="/users/{id}",
     *     requirements={"id"="\d+"},
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_item_operation_name"="put"
     *     }
     * )
     */
    public function putAction(User $data): User
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->_passwordEncoder->encodePassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
        }

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();

        return $data;
    }
}