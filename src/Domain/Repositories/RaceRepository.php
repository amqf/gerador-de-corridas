<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\AggregatedRace;

interface RaceRepository
{
    public function save(AggregatedRace $aggregatedRace) : AggregatedRace;
    public function getById(string $id) : ?AggregatedRace;
}