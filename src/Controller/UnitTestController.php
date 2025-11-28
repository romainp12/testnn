<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UnitTestController extends Controller
{
    private $_entityManager;
    private $_passwordEncoder;
    private $httpClient;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        HttpClientInterface $httpClient
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("unit/test", name="unit_test", methods="GET")
     */
    public function testUnit(Request $request)
    {
        $token = $request->headers->get('JWT-TOKEN');

        if (!$token) {
            return new JsonResponse(['error' => 'token missing'], JsonResponse::HTTP_FORBIDDEN);
        }

        $response = $this->httpClient->request(
            'GET', 
            'https://127.0.0.1:8000/api/users',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/ld+json',
                ],
                'verify_peer' => false,      // Désactiver la vérification SSL
                'verify_host' => false,      // Désactiver la vérification du host
            ]
        );

        return new JsonResponse([
            'status'            => $response->getStatusCode() === 200 ? 'OK' : 'ERROR',
            'users_endpoint'    => $response->getStatusCode(),
            'content'           => $response->toArray(),
        ]);
    }
}