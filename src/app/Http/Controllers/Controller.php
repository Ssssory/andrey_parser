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
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function __construct(
        private DashboardService $dashboardService
    )
    {       
    }

    public function index(Request $request): View|Factory
    {
        $countSources = Url::select(DB::raw('count(distinct(source)) as total'))->firstOrFail();

        $totalLinks = $this->dashboardService->getTotalLinks();

        $totalPoslovnaData = $this->dashboardService->getCountBySource(DirtyData::class);
        $totalRentData = $this->dashboardService->getCountBySource(DirtyStateData::class);
        $totalCarData = $this->dashboardService->getCountBySource(DirtyCarData::class);

        $messagerTelegram = $this->dashboardService->getSendingData(Transport::Telegram, SourceType::Car);
        $totalData = $this->dashboardService->getSumFromCollections([$totalCarData, $totalRentData, $totalPoslovnaData]);

        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'sources' => $countSources->total,
            'sourceTypes' => SourceType::cases(),
            'totalLinksCount' => Url::count(),
            'totalLinks' => $totalLinks,
            'totalData' => $totalData,

            'totalPoslovnaData' => $totalPoslovnaData,
            'totalRentData' => $totalRentData,
            'totalCarData' => $totalCarData,
            
            'messages' => $messagerTelegram['messages'],
            'messagesAll' => $messagerTelegram['messagesAll'],
            
        ]);
    }

    public function list(Request $request, string $model): View|Factory
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

    public function startPage(Request $request): View|Factory
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
