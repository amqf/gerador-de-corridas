<?php

namespace App\Presentation\Web\Controllers;

use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\ViewRace;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ViewRaceController implements Controller
{
    public function __construct(
        private ViewRace $viewRace
    )
    {
    }

    public function handle(Request $request, Response $response, array $args = []) : Response
    {
        /** @var AggregatedRace */
        $race = $this->viewRace->execute($args['id']);

        $response->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write(json_encode($race->toArray()));

        return $response;
    }
}