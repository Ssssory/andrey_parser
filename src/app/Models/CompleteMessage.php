<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompleteMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "model",
        "model_id",
        "message",
        "chat",
        "messenger",
        "type",
        "comment"
    ];
}
