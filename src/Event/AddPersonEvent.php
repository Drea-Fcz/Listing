<?php

namespace App\Event;

use App\Entity\Person;
use Symfony\Contracts\EventDispatcher\Event;

class AddPersonEvent extends Event
{
    const ADD_PERSON_EVENT = 'person.edit';

    public function __construct(private Person $person)
    {
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }
}
