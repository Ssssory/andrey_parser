<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyValueDictionary extends Model
{
    use Uuid;
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $guarded = [];

    public $timestamps = false;
}
