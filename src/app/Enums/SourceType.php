<?php

namespace App\Enums;

use Exception;

enum SourceType: string
{
    case Poslovna = 'poslovna';
    case Rent = 'rent';
    case Car = 'car';

    public static function getGroup(Sources $source)
    {
        return match ($source) {
            Sources::Poslovnabazasrbije, Sources::Companywall, Sources::Navidiku, Sources::Clutch, Sources::Yell => SourceType::Poslovna,
            Sources::Forzida, Sources::Halooglasi => SourceType::Rent,
            Sources::Polovniautomobili => SourceType::Car,
            default => throw new Exception("Error new source type"),
        };
    }
}