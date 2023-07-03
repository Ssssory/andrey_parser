<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirtyStateParametersData extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'property',
        'name',
        'value',
        'is_appruved',
    ];
}
