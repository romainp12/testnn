<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPassword
{
    protected $em;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(EntityManagerInterface $em, MailService $mailService) {
        $this->em = $em;
        $this->mailService = $mailService;
    }

    /**
     * @param User $data
     * @param UserRepository $repository
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return string
     */
    public function __invoke(User $data, UserRepository $repository, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $content = json_decode($request->getContent());
        $actualUser = $repository->findOneBy(array("email" => $content->email));

        if (!$actualUser) {
            throw new NotFoundHttpException("L'adresse email ne correspond à aucun utilisateur.");
        }

        if (in_array("ROLE_ADMIN", $actualUser->getRoles())) {
            throw new AccessDeniedHttpException("Il est impossible de modifier le mot de passe de l'administrateur");
        }

        $host = $request->server->get('HTTP_HOST');
        $host = "https://" . $host;
        $newEncodedPassword = $passwordEncoder->encodePassword($actualUser, $content->password);
        $actualUser->setTemporaryPassword($newEncodedPassword);
        $refreshToken = $this->em->getRepository(RefreshToken::class)->findOneBy(["username" => $actualUser->getEmail()]);

        try {
            $this->em->flush();
            $this->mailService->sendEmail(
                'blaise.pinheiro@gmail.com',
                $actualUser->getEmail(),
                "Changement de mot de passe",
                "email/resetPassword.html.twig",
                ["newPassword" => $content->password, "host" => $host, "accessToken" => $refreshToken]
            );
        } catch(Exception $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        return $actualUser;
    }
}