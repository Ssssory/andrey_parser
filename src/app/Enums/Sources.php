<?php 
namespace App\Enums;

enum Sources:string
{
    case Poslovnabazasrbije = 'poslovnabazasrbije';
    case Companywall = 'companywall';
    case Navidiku = 'navidiku';
    case Clutch = 'clutch';
    case Yell = 'yell';

    public static function getUrl(self $value)
    {
        return match ($value) {
            Sources::Poslovnabazasrbije => 'https://www.poslovnabazasrbije.rs/',
            Sources::Companywall => 'https://www.companywall.rs/',
            Sources::Navidiku => 'https://www.navidiku.rs/',
            Sources::Clutch => 'https://clutch.co/',
            Sources::Yell => 'https://www.yell.rs/',
        };
    }
}