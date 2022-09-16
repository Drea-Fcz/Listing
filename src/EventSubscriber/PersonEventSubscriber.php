<?php

namespace App\EventSubscriber;

use App\Event\AddPersonEvent;
use App\Service\MailerService;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PersonEventSubscriber implements EventSubscriber
{
    public function __construct(private MailerService $mailer)
    {
    }

    /**
     * @return array[]
     */
    public function getSubscribedEvents(): array
    {
       return [AddPersonEvent::ADD_PERSON_EVENT => ['onAddPersonEvent', 3000]
       ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onAddPersonEvent (AddPersonEvent $event) {
        $person = $event->getPerson();
        $mailMessage = $person->getFirstname().' '. $person->getName(). ' ' . 'need to be validate';
        $this->mailer->sendEmail(content: $mailMessage, subject: 'Mail send by EventSubscriber');
    }
}
