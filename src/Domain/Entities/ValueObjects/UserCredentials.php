<?php

namespace App\Domain\Entities\ValueObjects;

use App\Domain\Entities\ValueObjects\Exceptions\InvalidCredentialsException;

final class UserCredentials
{
    public function __construct(
        private string $email,
        private string $password,
    )
    {
        if(empty($email) || empty($password))
        {
            throw new InvalidCredentialsException('Email or password cannot be null');
        }
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }
}