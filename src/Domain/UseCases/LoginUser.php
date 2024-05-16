<?php

namespace App\Domain\UseCases;

session_start([
    'cookie_lifetime' => 86400,
]);

use App\Domain\Entities\LoggedUser;
use App\Domain\Entities\User;
use App\Domain\Entities\ValueObjects\UserCredentials;
use App\Domain\Repositories\UserRepository;
use App\Domain\UseCases\Exceptions\InvalidCredentialsException;

final class LoginUser
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function execute(UserCredentials $userCredentials) : LoggedUser
    {
        if(
            isset($_SESSION['id'])
            && isset($_SESSION['username'])
            && isset($_SESSION['email'])
        )
        {
            return LoggedUser::create($_SESSION);
        }

        /** @var User */
        $user = $this->userRepository->getByEmail($userCredentials->getEmail());

        if(!$this->userRepository->login($user))
        {
            throw new InvalidCredentialsException($user);
        }

        $_SESSION['id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();

        return LoggedUser::create($_SESSION);
    }
}