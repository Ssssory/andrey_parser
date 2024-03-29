<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id',
        'topic_name',
        'topic',
        'type',
        'scop',
        'transport',
    ];

    function bots()
    {
        return $this->belongsToMany(Bot::class);    
    }
}
