<?php

namespace App\Domain\Entities\ValueObjects;

use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use App\Domain\Entities\ValueObjects\Enums\RaceCancellationReason;

class RaceCancellation
{
    private function __construct(
        private DateTimeImmutable $canceledAt,
        private string $description,
        private RaceCancellationReason $reason
    )
    {
    }

    /**
     * This factory method ensure the current datetime with America/Sao_Paulo timezone canceledAt property
     * 
     * @param array $data [
     *  'description' => string
     *  'reason' => some case from \App\Domain\Entities\ValueObjects\Enums\RaceCancellationReason as a string
     * ]
     */
    public static function create(array $data) : self
    {
        static::ensureValidDescription($data['description']);

        return new self(
            new DateTimeImmutable(null, new DateTimeZone('America/Sao_Paulo')),
            $data['description'],
            RaceCancellationReason::fromString($data['reason'])
        );
    }

    private static function ensureValidDescription(string $description)
    {
        if(empty($description))
        {
            throw new InvalidArgumentException('Rance cancellation description cannot be empty');
        }
    }

    function toArray() : array
    {
        return [
            'canceled_at' => $this->canceledAt->format('Y-m-d H:i:s'),
            'description' => $this->description,
            'reason' => RaceCancellationReason::toString($this->reason),
        ];
    }
}
