<?php

namespace App\Domain\Entities\ValueObjects;

use Ramsey\Uuid\Uuid;
use App\Domain\Entities\ValueObjects\Exceptions\InvalidId;

class Id
{
    private function __construct(
        private string $value,
        private bool $notPersisted = false
    )
    {
    }

    public static function generate(): self
    {
        return new self(self::createUUID(), true);
    }

    public static function fromString(string $value): self
    {
        static::ensureIsValidIdString($value);
        return new self($value);
    }

    public static function create(string|null $id) : self
    {
        return $id === null ? Id::generate() : static::fromString($id);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function createUUID(): string
    {
        return Uuid::uuid4();
    }

    private static function ensureIsValidIdString(string $value) : void
    {
        if(!Uuid::isValid($value))
        {
            throw new InvalidId('Invalid id');
        }
    }
}
