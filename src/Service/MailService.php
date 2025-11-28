<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $template
     * @param $arrayValue
     */
    public function sendEmail($from, $to, $subject, $template, $arrayValue)
    {
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