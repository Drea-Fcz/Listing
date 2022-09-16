<?php

namespace App\EventListener;

use App\Event\AddPersonEvent;
use App\Event\ListAllPersonEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class PersonListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onPersonEdit(AddPersonEvent $event)
    {
        $this->logger->debug("une personne vient d'être ajouter " . $event->getPerson()->getName());
     }

     public function onPersonListAll( ListAllPersonEvent $event)
     {
         $this->logger->debug("Il y a  " . $event->getPerson() . " dans la list");
     }

     public function logKernelRequest(KernelEvent $event)
     {
         dd($event->getRequest());
     }
}
