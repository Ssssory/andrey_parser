<?php

namespace App\Services;

use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\CompleteMessage;
use App\Models\Url;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DashboardService
{
    public function getTotalLinks() : int 
    {
        $links = Url::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();
        return $links->sum('total');
    }

    public function getCountBySource(string $model) : Collection 
    {
        
        $result = $model::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();

        return $result->mapWithKeys(function ($item) {
                return [$item->source => $item->total];
            });
    }

    public function getSendingData(Transport $transport, SourceType $type = SourceType::Car): array
    {
        $totalSendToTelegram = CompleteMessage::select(DB::raw('messenger, count(messenger) as total'))
            ->groupBy('messenger')
            ->where('messenger', $transport)
            ->first();
            
        if ($type != SourceType::Car) {
            throw new Exception("add methods for another transport");
        }
        $lastDay = CompleteMessage::countTelegramCar()
            ->lastDay()
            ->get();
        $lastWeek = CompleteMessage::countTelegramCar()
            ->lastWeek()
            ->get();
        $lastMonth = CompleteMessage::countTelegramCar()
            ->lastMonth()
            ->get();

        $messages = [];
        $messages[$type->value] = [
            'day' => $lastDay,
            'week' => $lastWeek,
            'month' => $lastMonth,
        ];
        return [
            'messages' => $messages,
            'messagesAll' => $totalSendToTelegram->total,
        ];
    }

    function getSumFromCollections(array $collections) : int 
    {
        $sum = 0;
        foreach ($collections as $collect) {
            $sum += $collect->sum();
        }
        return $sum;
    }
}