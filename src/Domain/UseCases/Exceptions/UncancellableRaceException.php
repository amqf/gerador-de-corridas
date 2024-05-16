<?php

namespace App\Domain\UseCases\Exceptions;

use DomainException;

final class UncancellableRaceException extends DomainException
{
}