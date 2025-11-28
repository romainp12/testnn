<?php
// api/src/EventSubscriber/BookMailSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class UserMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $from = "blaise.pinheiro@gmail.com";
        $to = $user->getEmail();
        $subject = "creation compte utilisateur";
        $template = "email/confirmSubscription.html.twig";
        $arrayValue = ["user" => $user->getEmail()];

        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate($template)

            // pass variables (name => value) to the template
            ->context($arrayValue)
        ;

        $this->mailer->send($email);
    }
}