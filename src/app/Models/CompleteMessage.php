<?php

namespace App\Models;

use App\Enums\Transport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeLastDay(Builder $query)
    {
        $query->where('created_at', '>=', now()->subDay()->startOfDay());
    }

    public function scopeLastWeek(Builder $query)
    {
        $query->where('created_at', '>=', now()->subWeek()->startOfDay());
    }

    public function scopeLastMonth(Builder $query)
    {
        $query->where('created_at', '>=', now()->subMonth()->startOfDay());
    }

    public function scopeCountTelegramCar(Builder $query)
    {
        $query->select(DB::raw('count("id") as count'))
            ->where('messenger', Transport::Telegram)
            ->where('model', DirtyCarData::class);
    }

    public function scopeCountTelegramRent(Builder $query)
    {
        $query->select(DB::raw('count("id") as count'))
            ->where('messenger', Transport::Telegram)
            ->where('model', DirtyStateData::class);
    }

    public function scopeCountTelegramPoslovna(Builder $query)
    {
        $query->select(DB::raw('count("id") as count'))
            ->where('messenger', Transport::Telegram)
            ->where('model', DirtyData::class);
    }
}
