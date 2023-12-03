<?php

namespace App\Services;

use App\Models\DirtyCarData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

final class CarService
{
    private int $minPriceForBrand = 3000;

    private array $selectForSend = [
        'dirty_car_data.id',
        'dirty_car_data.url',
        'dirty_car_data.name',
        'dirty_car_data.brand',
        'dirty_car_data.price',
        'dirty_car_data.images'
    ];

    public function __construct(
        private ParametersService $parametersService,
    ) {
    }

    /**
     * @param boolean $isDebug
     * @return Collection<DirtyCarData>
     */
    public function getFreshCars(bool $isDebug): Collection
    {
        return DirtyCarData::with('dirtyCarParametersData')
            ->when(!$isDebug, function ($query) {
                $query->whereDate('dirty_car_data.created_at', '>=', now()->subMinutes(15));
            })
            ->leftJoin('complete_messages', function ($join) {
                $join->on('dirty_car_data.id', '=', 'complete_messages.model_id')
                    ->where('complete_messages.model', DirtyCarData::class);
            })
            ->whereNull('complete_messages.model_id')
            ->limit(100)
            ->orderBy('dirty_car_data.created_at', 'desc')
            ->get($this->selectForSend);
    }


    public function fillterByBrand(Collection $all, $topicesNames): Collection
    {
        // get all keys for dictionary name brand
        $properties = $this->parametersService->getBrendDirtyParametersKeys();

        $arrBrands = $all->filter(function ($item) use ($topicesNames, $properties) {
            if ($this->minPriceForBrand > $this->convertPriceToNumber($item->price)) {
                $current = $item->dirtyCarParametersData->filter(function ($item) use ($properties) {
                    return in_array($item->property, $properties);
                });
                $brand = $current->first()->value;
                if (in_array($brand, $topicesNames->toArray())) {
                    $item->brand = $current->first()->value;
                    return $item;
                }
            }
        })->groupBy(function ($item) {
            return $item->brand;
        });

        return $arrBrands;
    }

    public function calculateBrands(Collection $all): SupportCollection
    {
        return $all->groupBy(function ($item) {
            return $item->brand;
        })->map(function ($item) {
            return $item->count();
        })->sortDesc();
    }


    public function fillterByInexpensive(Collection $all, int $low = null, int $high = null) : SupportCollection 
    {
        if ($low && $high) {
            return $all->filter(function ($item) use ($low, $high) {
                $price = $this->convertPriceToNumber($item->price);
                if ($price < $high && $price > $low) {
                    return $item;
                }
            });
        }
        return $all->filter(function ($item) {
            if ($this->convertPriceToNumber($item->price) < 3000) {
                return $item;
            }
        });
    }

    public function convertPriceToNumber(string $price) : int
    {
        $price = str_replace([' €','.',','],'',$price);
        return (int) $price;
    }
}
