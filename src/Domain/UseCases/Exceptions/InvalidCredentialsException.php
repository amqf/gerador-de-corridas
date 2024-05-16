<?php

namespace App\Domain\UseCases\Exceptions;

use App\Domain\Entities\User;
use DomainException;

final class InvalidCredentialsException extends DomainException
{
    public function __construct(private User $user)
    {
        parent::__construct();
    }

    public function getUser() : User
    {
        return $this->user;
    }
}