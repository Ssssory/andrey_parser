<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirtyCarData extends Model
{
    use HasFactory;

    protected $guarded = [];

    function dirtyCarParametersData()
    {
        return $this->hasMany(DirtyCarParametersData::class, 'car_id', 'id');
    }
}
