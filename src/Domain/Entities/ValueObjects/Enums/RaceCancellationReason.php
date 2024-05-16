<?php

namespace App\Domain\Entities\ValueObjects\Enums;

use Exception;

enum RaceCancellationReason
{
    case Others;
    case ForceMajeure;

    function makeDescription(RaceCancellationReason $reason) : string
    {
        switch ($reason) {
            case RaceCancellationReason::Others:
                echo "The race was canceled for other reasons.";
                break;
            case RaceCancellationReason::ForceMajeure:
                return "The race was canceled due to unforeseen and unavoidable circumstances.";
                break;
        }
    }

    public static function fromString(string $reason) : self
    {
        return match($reason) {
            'ForceMajeure' => RaceCancellationReason::ForceMajeure,
            default => RaceCancellationReason::Others,
        };
    }

    public static function toString(RaceCancellationReason $reason): string
    {
        return match ($reason) {
            RaceCancellationReason::ForceMajeure => 'ForceMajeure',
            default => 'Others',
        };
    }

    function isValid($value): bool {
        return match($value) {
            RaceCancellationReason::ForceMajeure,
            RaceCancellationReason::Others => true,
            default => false,
        };
    }
}