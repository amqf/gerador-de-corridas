<?php

namespace App\Presentation\Web\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Adapters\Repositories\UserRepository;

final class UserController implements Controller
{
    function __construct(private UserRepository $userRepository)
    {
    }

    public function handle(Request $request, Response $response, array $args = []) : Response
    {

        var_dump($this->userRepository->getByEmail());
        $responseBody = json_encode([
            'sucesso' => true,
        ]);

        $response->withHeader('Content-Type', 'application/json')
                 ->getBody()
                 ->write($responseBody);

        return $response;
    }
}