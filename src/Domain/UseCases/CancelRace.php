<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RaceRepository;
use Respect\Validation\Exceptions\ValidationException;
use Rakit\Validation\Validator;
use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\Exceptions\RaceNotFoundException;

final class CancelRace
{
    function __construct(
        private RaceRepository $raceRepository
    )
    {
    }

    /**
     * @param array $data [
     *  'id' => \App\Domain\Entities\ValueObjects\Id,
     *  'description' => string
     *  'reason' => some case from \App\Domain\Entities\ValueObjects\Enums\RaceCancellationReason as a string
     * ]
     */
    public function execute(string $id, array $data) : AggregatedRace
    {
        $data['id'] = $id;

        $this->ensureValidInputData($data);

        /** @var AggregatedRace $race */
        if(!$race = $this->raceRepository->getById($data['id']))
        {
            throw new RaceNotFoundException(sprintf('Race not found with id %s', $data['id']));
        }

        $race->cancel($data);

        return $this->raceRepository->save($race);
    }

    // Validate structure and data format only
    private function ensureValidInputData(array $data) : array
    {
        $validator = new Validator;

        $validation = $validator->make($data, [
            'id' => 'required|alpha',
            'description' => 'required|alpha',
        ]);

        $validation->validate();

        return $data;
    }
}