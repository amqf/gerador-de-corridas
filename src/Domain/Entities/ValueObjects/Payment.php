<?php

namespace App\Domain\Entities\ValueObjects;

use DateTimeImmutable;

final class Payment
{
    private function __construct(
        private float $amount,
        private PaymentTimestamp $timestamp
    )
    {
        if ($this->amount <= 0)
        {
            throw new InvalidArgumentException("Payment amount must be positive.");
        }
    }

    public static function create(array $data) : self
    {
        return new self(
            $data['amount'],
            $timestamp = PaymentTimestamp::create($data['timestamp'])
        );
    }

    public static function make(array $data) : self
    {
        return new self(
            $data['amount'],
            $timestamp = PaymentTimestamp::make($data['timestamp'])
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
