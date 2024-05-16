<?php

namespace App\Domain\Entities\ValueObjects;
use DateTimeImmutable;
use DateTimeZone;

final class TransactionTimestamp
{
    private function __construct(
        private DateTimeImmutable|null $timestamp
    )
    {
    }

    public static function create(string|null $timestamp)
    {
        if(is_string($timestamp))
        {
            return new self(new DateTimeImmutable($timestamp, new DateTimeZone('America/Sao_Paulo')));
        }

        return new self($timestamp);
    }

    public function getTimestamp(): DateTimeImmutable|null
    {
        return $this->timestamp;
    }

    public function __toString()
    {
        return $this->timestamp->format('Y-m-d H:i:s');
    }
}
