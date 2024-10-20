<?php

namespace App\Services;

use App\Enums\Sources;
use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\CompleteMessage;
use App\Models\Url;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DashboardService
{
    public function getTotalLinks(): Collection
    {
        $links = Url::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();

        return $links->mapWithKeys(function ($item) {
            return [SourceType::getGroup(Sources::from(strtolower($item->source)))->value => $item->total];
        });
    }

    public function getCountBySource(string $model) : Collection 
    {
        $result = $model::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();

        return $result->mapWithKeys(function ($item) {
                return [SourceType::getGroup(Sources::from(strtolower($item->source)))->value => $item->total];
            });
    }

    public function getSendingAllMessages(Transport $transport): int
    {
        return  CompleteMessage::select(DB::raw('messenger, count(messenger) as total'))
        ->groupBy('messenger')
        ->where('messenger', $transport)
        ->first()->total;
    }

    public function getSendingData(Transport $transport, SourceType $type = SourceType::Car): array
    {
        if ($type === SourceType::Car) {
            $lastDay = CompleteMessage::countTelegramCar()
                ->lastDay()
                ->get();
            $lastWeek = CompleteMessage::countTelegramCar()
                ->lastWeek()
                ->get();
            $lastMonth = CompleteMessage::countTelegramCar()
                ->lastMonth()
                ->get();
        } elseif ($type === SourceType::Rent) {
            $lastDay = CompleteMessage::countTelegramRent()
                ->lastDay()
                ->get();
            $lastWeek = CompleteMessage::countTelegramRent()
                ->lastWeek()
                ->get();
            $lastMonth = CompleteMessage::countTelegramRent()
                ->lastMonth()
                ->get();
        } elseif ($type === SourceType::Poslovna) {
            $lastDay = CompleteMessage::countTelegramPoslovna()
                ->lastDay()
                ->get();
            $lastWeek = CompleteMessage::countTelegramPoslovna()
                ->lastWeek()
                ->get();
            $lastMonth = CompleteMessage::countTelegramPoslovna()
                ->lastMonth()
                ->get();
        } else {
            throw new Exception("add methods for another transport");
        }

        $messages = [];
        $messages[$type->value] = [
            'day' => $lastDay,
            'week' => $lastWeek,
            'month' => $lastMonth,
        ];
        return [
            'messages' => $messages,
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