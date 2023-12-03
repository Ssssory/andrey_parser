<?php

namespace App\Models;

use App\Enums\SendScop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'type',
        'scop',
        'transport',
    ];

    function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    function getScopAttribute(string $value): SendScop|null
    {
        if ($value) {
            return SendScop::from($value);
        }
        return null;
    }
}
