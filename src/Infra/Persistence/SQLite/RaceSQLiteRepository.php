<?php

namespace App\Infra\Persistence\SQLite;

use App\Domain\Repositories\RaceRepository;
use App\Domain\Entities\AggregatedRace;

final class RaceSQLiteRepository extends SQLiteRepository implements RaceRepository
{
    protected string $table = 'races';

    function __construct(string $dbFilePath = '')
    {
        parent::__construct(!empty($dbFilePath) ? $dbFilePath : '../databases/database.db');
    }

    public function save(AggregatedRace $aggregatedRace) : AggregatedRace
    {
        if($aggregatedRace->notPersisted())
        {
            return AggregatedRace::make($this->create($aggregatedRace->toArray()));
        }
            
        if(!$this->update($aggregatedRace->getId(), $aggregatedRace->toArray()))
        {
            throw new RuntimeException(sprintf('Cannot persist %s', $aggregatedRace->getId()));
        }

        return AggregatedRace::make($this->findById($aggregatedRace->getId()));
    }

    public function getById(string $id) : ?AggregatedRace
    {
        if($race = $this->findById($id))
        {
            return AggregatedRace::make($race);
        }

        return null;
    }
}