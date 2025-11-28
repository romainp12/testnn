<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiUserController extends AbstractController
{
    private $em;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(MailService $mailService, EntityManagerInterface $em)
    {
        $this->mailService = $mailService;
        $this->em = $em;
    }

    /**
     * @Route("reset/confirm/password/{token}", name="confirm_password")
     * @param string $token
     */
    public function confirmResetPassword(string $token) :RedirectResponse
    {
        $refreshToken = $this->em->getRepository(RefreshToken::class)->findOneBy(["refreshToken" => $token]);

        if (!$refreshToken) {
            throw new NotFoundHttpException("Ce token n'existe pas en base. L'utilisateur n'a pas été identifié.");
        }

        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $refreshToken->getUsername()]);
        $user->setPassword($user->getTemporaryPassword());

        try {
            $this->em->flush();
        } catch(Exception $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->redirect('https://127.0.0.1:8000/api/docs');
    }
}