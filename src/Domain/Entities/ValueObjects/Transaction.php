<?php

namespace App\Domain\Entities\ValueObjects;

use DateTimeImmutable;

final class Transaction
{
    private function __construct(
        private float $amount,
        private TransactionTimestamp $timestamp
    )
    {
        if ($this->amount <= 0)
        {
            throw new InvalidArgumentException("Transaction amount must be positive.");
        }
    }

    public static function create(array $data)
    {
        return new self(
            $data['amount'],
            $timestamp = TransactionTimestamp::create($data['timestamp'])
        );
    }

    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'timestamp' => $this->timestamp->__toString(),
        ];
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->getTimestamp;
    }

}
