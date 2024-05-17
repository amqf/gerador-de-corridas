<?php

namespace App\Presentation\Web\Controllers;

use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\CancelRace;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


final class CancelRaceController implements Controller
{
    public function __construct(
        private CancelRace $cancelRace
    )
    {
    }

    public function handle(Request $request, Response $response, array $args = []) : Response
    {
        /** @var AggregatedRace */
        $race = $this->cancelRace->execute($args['id'], $request->getParsedBody());

        $response->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write(json_encode($race->toArray()));

        return $response;
    }
}