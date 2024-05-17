<?php

use App\Domain\Entities\ValueObjects\GeoCoordinate;
use App\Domain\Entities\ValueObjects\Exceptions\InvalidGeoCoordinateException;

test('latitude cannot be lass than -90', function () {
    //Arrange
    $coordinates = [
        'latitude' => -91,
        'longitude' => 25,
    ];

    expect(fn () => GeoCoordinate::create($coordinates))->toThrow(InvalidGeoCoordinateException::class);
});

test('latitude cannot be bigger than 90', function () {
    //Arrange
    $coordinates = [
        'latitude' => 91,
        'longitude' => 25,
    ];

    expect(fn () => GeoCoordinate::create($coordinates))->toThrow(InvalidGeoCoordinateException::class);
});

test('longitude cannot be lass than -180', function () {
    //Arrange
    $coordinates = [
        'latitude' => 25,
        'longitude' => -181,
    ];

    expect(fn () => GeoCoordinate::create($coordinates))->toThrow(InvalidGeoCoordinateException::class);
});

test('longitude cannot be bigger than 180', function () {
    //Arrange
    $coordinates = [
        'latitude' => 25,
        'longitude' => 181,
    ];

    expect(fn () => GeoCoordinate::create($coordinates))->toThrow(InvalidGeoCoordinateException::class);
});
