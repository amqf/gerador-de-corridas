<?php

namespace App\Domain\UseCases;

use App\Domain\Entities\NewUser;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;

final class RegisterUser
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function execute(NewUser $newUser) : User
    {
        /** @var User */
        $user = $this->userRepository->register($newUser);
        return $user;
    }
}