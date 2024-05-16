<?php

require './vendor/autoload.php';

use App\Domain\Entities\AggregatedRace;
use App\Domain\UseCases\CreateRace;
use App\Domain\UseCases\CancelRace;
use App\Infra\Persistence\SQLite\RaceSQLiteRepository;

it('can cancel a race', function () {
    // Arrange

    /** @var CreateRace $createRaceUseCase */
    $createRaceUseCase = new CreateRace(
        new RaceSQLiteRepository('./databases/database_test.db')
    );

    /** @var CancelRace $cancelRaceUseCase */
    $cancelRaceUseCase = new CancelRace(
        new RaceSQLiteRepository('./databases/database_test.db')
    );

    /** @var array $cancellation */
    $cancellation = [
        'description' => 'Desisti',
        'reason' => 'Others',
    ];

    /** @var array $race */
    $race = [
         "origin" => [
            "latitude" => 40.7128,
            "longitude" => -74.0060,
        ],
        "destiny" => [
            "latitude" => 34.0522,
            "longitude" => -118.2437 
        ],
        "transaction" => [
            "amount" => 100, 
            "timestamp" => "2024-05-15 12:30:00" 
        ] 
    ];

    /** @var AggregatedRace */
    $createdRace = $createRaceUseCase->execute($race);
    
    // Act
    $createdRace->cancel($cancellation);
    $cancelRaceUseCase->execute($createdRace->getId(), $createdRace->toArray());

    // Assert
    expect($response)->toBeInstanceOf(AggregatedRace::class);
});