<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum SendScop:string {
    case Forum = 'forum';
    case Inexpensive = 'inexpensive';
    case InexpensiveForum = 'inexpensive forum';
    case Handle = 'handle';

    public static function getValues(): array
    {
        return Arr::map(self::cases(), fn ($value) => $value->value);
    }
}