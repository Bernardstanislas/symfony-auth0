<?php

namespace App\Domain\User\Entity;

class AnonymousUser extends User {
    public function __construct()
    {
        parent::__construct(
            null,
            ['IS_AUTHENTICATED_ANONYMOUSLY']
        );
    }

    public function getUsername()
    {
        return null;
    }
}
