<?php

namespace App\Presentation\Web\Controllers;

use App\Domain\Entities\AggregatedRace;
use App\Domain\Repositories\UserRepository;
use App\Domain\UseCases\CreateRace;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


final class CreateRaceController implements Controller
{
    public function __construct(
        private CreateRace $createRace
    )
    {
    }

    public function handle(Request $request, Response $response, array $args = []) : Response
    {
        // var_dump();die();
        /** @var AggregatedRace */
        $race = $this->createRace->execute($request->getParsedBody());

        $response->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write(json_encode($race->toArray()));

        return $response;
    }
}