<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'type',
        'transport',
    ];

    function groups() 
    {
        return $this->belongsToMany(Group::class);
    }
}
