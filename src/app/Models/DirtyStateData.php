<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirtyStateData extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'hash',
        'url',
        'name',
        'images',
        'description',
        'address',
        'phone',
        'city',
        'price',
        'type',
        'shape',
        'owner',
    ];

    function dirtyStateParametersData()
    {
        return $this->hasMany(DirtyStateParametersData::class, 'state_id', 'id');
    }
}
