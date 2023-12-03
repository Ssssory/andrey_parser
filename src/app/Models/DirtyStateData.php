<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    function dirtyStateParametersData(): HasMany
    {
        return $this->hasMany(DirtyStateParametersData::class, 'state_id', 'id');
    }
}
