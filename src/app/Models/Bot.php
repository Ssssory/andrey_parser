<?php

namespace App\Models;

use App\Enums\SendScop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    function groups() 
    {
        return $this->belongsToMany(Group::class);
    }

    function getScopAttribute($value)
    {
        if ($value) {
            return SendScop::from($value);
        }
        return null;
    }
}
