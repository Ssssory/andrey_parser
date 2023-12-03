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

    public function scopeLastDay(Builder $query): void
    {
        $query->whereDate('created_at', '>=', now()->subDays(1));
    }

    public function scopeLastWeek(Builder $query): void
    {
        $query->whereDate('created_at', '>=', now()->subWeeks(1));
    }

    public function scopeLastMonth(Builder $query): void
    {
        $query->whereDate('created_at', '>=', now()->subMonths(1));
    }

    public function scopeCountTelegramCar(Builder $query): void
    {
        $query->select(DB::raw('count("id") as count'))
            ->where('messenger', Transport::Telegram)
            ->where('model', DirtyCarData::class);
    }
}
