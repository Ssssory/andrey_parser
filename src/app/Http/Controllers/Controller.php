<?php

namespace App\Http\Controllers;

use App\Enums\Sources;
use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\DirtyCarData;
use App\Models\DirtyData;
use App\Models\DirtyStateData;
use App\Models\Url;
use App\Services\DashboardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function __construct(
        private DashboardService $dashboardService
    )
    {
    }

    public function index(Request $request)
    {
        $countSources = Url::select(DB::raw('count(distinct(source)) as total'))->firstOrFail();

        $totalLinks = $this->dashboardService->getTotalLinks();

        $totalPoslovnaData = $this->dashboardService->getCountBySource(DirtyData::class);
        $totalRentData = $this->dashboardService->getCountBySource(DirtyStateData::class);
        $totalCarData = $this->dashboardService->getCountBySource(DirtyCarData::class);

        $messagerTelegramCar = $this->dashboardService->getSendingData(Transport::Telegram, SourceType::Car);
        $messagerTelegramRent = $this->dashboardService->getSendingData(Transport::Telegram, SourceType::Rent);
        $messagerTelegramPoslovna = $this->dashboardService->getSendingData(Transport::Telegram, SourceType::Poslovna);
        $messagerTelegramAll = $this->dashboardService->getSendingAllMessages(Transport::Telegram);

        $mapCarData = (new Collection([
            'name' => SourceType::Car->value,
            'total' => $totalCarData[SourceType::Car->value],
            'urls' => $totalLinks[SourceType::Car->value],
            'messages' => [
                'day' => $messagerTelegramCar['messages'][SourceType::Car->value]['day']->first()->count,
                'week' => $messagerTelegramCar['messages'][SourceType::Car->value]['week']->first()->count,
                'month' => $messagerTelegramCar['messages'][SourceType::Car->value]['month']->first()->count,
            ]
        ]))->toArray();

        $mapRentData = (new Collection([
            'name' => SourceType::Rent->value,
            'total' => $totalRentData[SourceType::Rent->value],
            'urls' => $totalLinks[SourceType::Rent->value],
            'messages' => [
                'day' => $messagerTelegramRent['messages'][SourceType::Rent->value]['day']->first()->count,
                'week' => $messagerTelegramRent['messages'][SourceType::Rent->value]['week']->first()->count,
                'month' => $messagerTelegramRent['messages'][SourceType::Rent->value]['month']->first()->count,
            ]
        ]))->toArray();

        $mapPoslovnaData = (new Collection([
            'name' => SourceType::Poslovna->value,
            'total' => $totalPoslovnaData[SourceType::Poslovna->value]??0,
            'urls' => $totalLinks[SourceType::Poslovna->value]??0,
            'messages' => [
                'day' => $messagerTelegramPoslovna['messages'][SourceType::Poslovna->value]['day']->first()->count,
                'week' => $messagerTelegramPoslovna['messages'][SourceType::Poslovna->value]['week']->first()->count,
                'month' => $messagerTelegramPoslovna['messages'][SourceType::Poslovna->value]['month']->first()->count,
            ]
        ]))->toArray();
        // dd($mapCarData);

        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'sources' => $countSources->total,
            'sourceTypes' => SourceType::cases(),
            'totalLinksCount' => Url::count(),

            'totalPoslovnaData' => $mapPoslovnaData,
            'totalRentData' => $mapRentData,
            'totalCarData' => $mapCarData,

            'messagesAll' => $messagerTelegramAll,

        ]);
    }

    public function list(Request $request, string $model)
    {
        if (!$model) {
            throw new Exception("Error Processing Request");
        }

        $source = Sources::from($model);
        $list = DirtyData::where('source', $source->name)->paginate(15);
        $count = DirtyData::where('source', $source->name)->count();

        return view('pages.poslovna.table', [
            'title' => $model,
            'list' => $list,
            'count' => $count,
        ]);
    }

    public function startPage(Request $request)
    {
        $url = $request->input('url', null);
        $site = $request->input('site', null);

        $urls = Url::where('category', 'handle')->get();

        if ($url) {
            if (!$site) {
                session()->flash('message', 'Site not selected');
            }else{
                $existUrl = Url::where('url', $url)->where('source', $site)->exists();
                if(!$existUrl){
                    $newUrl = new Url();
                    $newUrl->url = $url;
                    $newUrl->source = $site;
                    $newUrl->category = 'handle';
                    $newUrl->save();
                    session()->flash('message', 'Site saved');
                }
            }
        }

        return view('pages.start',[
            'title' => 'Добавить ссылку на ресурс',
            'select' => Sources::cases(),
            'urls' => $urls,
        ]);
    }
}
