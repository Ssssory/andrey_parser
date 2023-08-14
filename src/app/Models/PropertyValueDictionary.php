<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyValueDictionary extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $guarded = [];

    public $timestamps = false;
}
