<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RaceRepository;
use Respect\Validation\Exceptions\ValidationException;
use Rakit\Validation\Validator;
use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\Exceptions\RaceNotFoundException;

final class ViewRace
{
    function __construct(
        private RaceRepository $raceRepository
    )
    {
    }

    /**
     * @param string $id \App\Domain\Entities\ValueObjects\Id
     */
    public function execute(string $id) : AggregatedRace
    {
        /** @var AggregatedRace $race */
        if(!$race = $this->raceRepository->getById($id))
        {
            throw new RaceNotFoundException(sprintf('Race not found with id %s', $id));
        }

        return $race;
    }
}