<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDictionary extends Model
{
    use Uuid;
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;
}
