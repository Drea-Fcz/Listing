<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerService
{
    #[Route('/email')]
    public function __construct(private MailerInterface $mailer, private $replyTo )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $to = 'fcz.audre@gmail.com',
        $subject = 'Time for Symfony Mailer!'
    ): void
    {
        $email = (new Email())
            ->from('fcz.audre@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

         $this->mailer->send($email);
    }

}
