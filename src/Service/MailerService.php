<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerService
{
    public function __construct(private MailerInterface $mailer, private $replyTo )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        $to = 'otw67965@nezid.com',
        $content = '<p>See Twig integration for better HTML integration!</p>'
    ): void
    {
        $email = (new Email())
            ->from('fcz.audre@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($content);

         $this->mailer->send($email);
    }

}
