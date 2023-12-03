<?php

namespace App\Enums;

use Exception;
use Illuminate\Support\Arr;

enum SourceType: string
{
    case Poslovna = 'poslovna';
    case Rent = 'rent';
    case Car = 'car';

    public static function getGroup(Sources $source): SourceType
    {
        return match ($source) {
            Sources::Poslovnabazasrbije, Sources::Companywall, Sources::Navidiku, Sources::Clutch => SourceType::Poslovna,
            Sources::Forzida, Sources::Halooglasi => SourceType::Rent,
            Sources::Polovniautomobili => SourceType::Car,
            default => throw new Exception("Error new source type"),
        };
    }

    public static function getValues() : array {
        return Arr::map(self::cases(), fn($value) => $value->value);
    }
}