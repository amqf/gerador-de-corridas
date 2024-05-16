<?php

namespace App\Domain\Entities\ValueObjects;

use Domain\Common\Exceptions\InvalidGeoCoordinateException;

class GeoCoordinate
{
    private $latitude;
    private $longitude;

    private function __construct(float $latitude, float $longitude)
    {
        $this->ensureIsValidLatitude($latitude);
        $this->ensureIsValidLongitude($longitude);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function create(array $coordinates) : self
    {
        return static::fromCoordinates($coordinates['latitude'], $coordinates['longitude']);
    }

    private static function fromCoordinates(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function equals(GeoCoordinate $other): bool
    {
        return $this->latitude === $other->latitude && $this->longitude === $other->longitude;
    }

    public function toArray() : array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->latitude,
        ];
    }

    private function ensureIsValidLatitude(float $latitude): void
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidGeoCoordinateException("Invalid latitude: $latitude. It must be between -90 and 90.");
        }
    }

    private function ensureIsValidLongitude(float $longitude): void
    {
        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidGeoCoordinateException("Invalid longitude: $longitude. It must be between -180 and 180.");
        }
    }
}