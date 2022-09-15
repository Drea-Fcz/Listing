<?php

namespace App\Service;


use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class Helpers
{
    public function __construct(private Security $security)
    {
    }

    public function sayCc(): string
    {
        return 'Coucou';
    }

    public function getUser(): User
    {
       return $this->security->getUser();
    }

}
