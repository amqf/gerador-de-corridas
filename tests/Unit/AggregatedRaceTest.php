<?php

use App\Domain\Entities\AggregatedRace;
use App\Domain\Entities\ValueObjects\Payment;

it("cannot pay a cancelled race", function () {
    // Arrange
    $aggregatedRace = AggregatedRace::create([
        "origin" => [
            "latitude" => -22.302407981128297,
            "longitude" => -49.10229971613744
        ],
        "destiny" => [
            "latitude" => -22.302715314470994,
            "longitude" => -49.101353497779776
        ]
    ]);

    $aggregatedRace->cancel([
        "description" => "I gave up",
        "reason" => "Others"
    ]);

    expect(fn () => $aggregatedRace->pay([
        'amount' => 30.0,
    ]))->toThrow(\DomainException::class);

});

it("cannot pay with with value lass than the race cost", function () {
    // Arrange
    $aggregatedRace = AggregatedRace::create([
        "origin" => [
            "latitude" => "-22.302407981128297",
            "longitude" => "-49.10229971613744"
        ],
        "destiny" => [
            "latitude" => "-22.302715314470994",
            "longitude" => "-49.101353497779776"
        ]
    ]);

    expect(fn () => $aggregatedRace->pay([
        'amount' => 1,
    ]))->toThrow(\DomainException::class);
});

it("can pay with with value bigger than the race cost", function () {
    // Arrange
    $aggregatedRace = AggregatedRace::create([
        "origin" => [
            "latitude" => "-22.302407981128297",
            "longitude" => "-49.10229971613744"
        ],
        "destiny" => [
            "latitude" => "-22.302715314470994",
            "longitude" => "-49.101353497779776"
        ]
    ]);

    $aggregatedRace->pay([
        'amount' => 10,
    ]);

    expect($aggregatedRace->getPayment())->toBeInstanceOf(Payment::class);
});