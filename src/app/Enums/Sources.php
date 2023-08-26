<?php 
namespace App\Enums;

enum Sources:string
{
    // business
    case Poslovnabazasrbije = 'poslovnabazasrbije';
    case Companywall = 'companywall';
    case Navidiku = 'navidiku';
    case Clutch = 'clutch';
    case Yell = 'yell';
    // real estate
    case Forzida = 'forzida';
    case Halooglasi = 'halooglasi';

    // car
    case Polovniautomobili = 'polovniautomobili';

    public static function getUrl(self $value)
    {
        return match ($value) {
            Sources::Poslovnabazasrbije => 'https://www.poslovnabazasrbije.rs/',
            Sources::Companywall => 'https://www.companywall.rs/',
            Sources::Navidiku => 'https://www.navidiku.rs/',
            Sources::Clutch => 'https://clutch.co/',
            Sources::Yell => 'https://www.yell.rs/',
            Sources::Forzida => 'https://www.4zida.rs/',
            Sources::Halooglasi => 'https://www.halooglasi.com/',
            Sources::Polovniautomobili => 'https://www.polovniautomobili.com/',
        };
    }
}