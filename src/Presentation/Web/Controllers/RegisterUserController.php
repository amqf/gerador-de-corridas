<?php

namespace App\Presentation\Web\Controllers;

use App\Domain\Entities\NewUser;
use App\Domain\Repositories\UserRepository;
use App\Domain\UseCases\RegisterUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


final class RegisterUserController implements Controller
{
    public function __construct(
        private RegisterUser $registerUser
    )
    {
    }
    
    public function handle(Request $request, Response $response, array $args = []) : Response
    {
        $requestBody = $request->getParsedBody();

        // var_dump(file_get_contents('php://input'));
        // var_dump($_SERVER['CONTENT_LENGTH']);
        // var_dump(json_decode($request->getBody() . '', true));
        // var_dump($request->getHeaders());
        // var_dump($requestBody['username']);
        // die();

        /** @var NewUser $newUser */
        $newUser = NewUser::create($requestBody);

        /** @var User */
        $user = $this->registerUser->execute($newUser);

        /** @var string */
        $responseBody = json_encode($user->toArray());

        $response->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write($responseBody);

        return $response;
    }
}