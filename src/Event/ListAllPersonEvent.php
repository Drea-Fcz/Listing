<?php

namespace App\Event;

use App\Entity\Person;
use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonEvent extends Event
{
    const LIST_ALL_PERSON_EVENT = 'person.list.all';

    public function __construct(private int $nbrPerson)
    {
    }

    public function getPerson(): int
    {
        return $this->nbrPerson;
    }

}
