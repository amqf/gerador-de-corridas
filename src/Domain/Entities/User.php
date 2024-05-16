<?php

namespace App\Domain\Entities;

final class User
{
    private function __construct(
        private string $id,
        private string $username,
        private string $email,
        private string $password
    )
    {
    }

    public static function create(array $data) : User
    {
        return new User(
            $data['id'],
            $data['username'],
            $data['email'],
            $data['password']
        );
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
        ];
    }
}