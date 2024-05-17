<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RaceRepository;
use Respect\Validation\Exceptions\ValidationException;
use Rakit\Validation\Validator;
use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\Exceptions\RaceNotFoundException;
use DateTimeImmutable;
use DateTimeZone;

final class PayRace
{
    function __construct(
        private RaceRepository $raceRepository
    )
    {
    }

    /**
     * @param string $id \App\Domain\Entities\ValueObjects\Id
     */
    public function execute(string $id, array $data) : AggregatedRace
    {
        $data['timestamp'] = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));

        /** @var AggregatedRace $race */
        if(!$race = $this->raceRepository->getById($id))
        {
            throw new RaceNotFoundException(sprintf('Race not found with id %s', $id));
        }

        $race->pay($data);

        return $this->raceRepository->save($race);
    }
}