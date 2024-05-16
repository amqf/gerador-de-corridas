<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RaceRepository;
use Respect\Validation\Exceptions\ValidationException;
use Rakit\Validation\Validator;
use App\Domain\Entities\AggregatedRace;

final class CreateRace
{
    function __construct(
        private RaceRepository $raceRepository
    )
    {
    }

    public function execute(array $data) : AggregatedRace
    {
        $data = $this->ensureValidInputData($data);

        /** @var AggregatedRace */
        $aggregatedRace = AggregatedRace::create($data);

        $this->ensureIsntRaceInProgress($aggregatedRace);

        return $this->raceRepository->save($aggregatedRace);
    }

    // Validate structure and data format only
    private function ensureValidInputData(array $data) : array
    {
        // Criar uma instância do validador
        $validator = new Validator;

        unset($data['id']);

        // Definir regras de validação para a estrutura JSON
        $validation = $validator->make($data, [
            'origin.latitude' => 'required|numeric',
            'origin.longitude' => 'required|numeric',
            'destiny.latitude' => 'required|numeric',
            'destiny.longitude' => 'required|numeric',
            'transaction.amount' => 'numeric',
            'transaction.timestamp' => 'date:Y-m-d H:i:s',
        ]);

        // Executar a validação
        $validation->validate();

        return $data;
    }

    private function ensureIsntRaceInProgress(AggregatedRace $race) : void
    {
        if(false)
        {
            throw new RuntimeException('There is a race in progress. Cancel current race for start another.');
        }
    }
}