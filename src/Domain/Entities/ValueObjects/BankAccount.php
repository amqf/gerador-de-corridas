<?php

namespace App\Domain\Entities\ValueObjects;

use App\Domain\Entities\ValueObjects\Id;

final class BankAccount
{
    private function __construct(
        private Id $payerId,
        private Id $driverId
    )
    {
    }

    public function create(array $data)
    {
        return new self(
            Id::create($data['payerId']),
            Id::create($data['driverId']),
        );
    }
}