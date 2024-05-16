<?php

namespace App\Presentation\Web\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


final class HealthController implements Controller
{
    public function handle(Request $request, Response $response, array $args = []) : Response
    {
        $responseBody = json_encode([
            'status' => 'up',
            'timestamp' => date('h:i:s'),
        ]);

        // $response = $response->withHeader('Content-Type', 'application/json');

        $response->getBody()->write($responseBody);

        return $response;
    }
}