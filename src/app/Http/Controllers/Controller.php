<?php

namespace App\Http\Controllers;

use App\Enums\Sources;
use App\Models\DirtyCarData;
use App\Models\DirtyData;
use App\Models\DirtyStateData;
use App\Models\Url;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DiDom\Document;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $countSources = Url::select(DB::raw('count(distinct(source)) as total'))->firstOrFail();

        $links = Url::select(DB::raw('lower(source) as source, count(source) as total'))->groupBy('source')->get();
        $totalLinks = $this->prepareCounts($links);

        $dirtyData = DirtyData::select(DB::raw('lower(source) as source, count(source) as total'))->groupBy('source')->get();
        $totalData = $this->prepareCounts($dirtyData);

        $dirtyRentData = DirtyStateData::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();
        $totalRentData = $this->prepareCounts($dirtyRentData);

        $dirtyCarData = DirtyCarData::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();
        $totalCarData = $this->prepareCounts($dirtyCarData);

        // dd($dirtyRentData);

        $activeLinks = Url::select(DB::raw('source, count(source) as total'))->where('status', 'in progress')->groupBy('source')->get();
        $activeLinksData = $this->prepareCounts($activeLinks);
        // dump($activeLinks);
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'sources' => $countSources->total,
            'totalLinksCount' => Url::count(),
            'totalLinks' => $totalLinks,
            'totalData' => $totalCarData + $totalRentData + $totalData,
            'activeLinksData' => $activeLinksData,
        ]);
    }

    public function prepareCounts($items)
    {
        $totalData = [];
        foreach ($items as $item) {
            $totalData[$item->source] = $item->total;
        }
        return $totalData;
    }

    public function list(Request $request, string $model)
    {
        if (!$model) {
            throw new Exception("Error Processing Request");
        }

        $source = Sources::from($model);
        $list = DirtyData::where('source', $source->name)->limit(10)->get();
        $count = DirtyData::where('source', $source->name)->count();

        return view('pages.table', [
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

    public function saveCsv()
    {
        // $data = DirtyData::limit(10)->get();
        $data = DirtyData::all();
        Storage::disk('public')->put('test.csv', '');
        // $str = '';
        foreach ($data as $key => $value) {
            $codeNmae = explode(' - ', $value->name);
            $fullAddress = explode(',', $value->address);
            $city = array_shift($fullAddress);
            $str = '';
            $str .= $codeNmae[0]  . ';';
            $str .= $codeNmae[1]  . ';';
            // $str .= $value->name . ';';
            $str .= $city  . ';';
            $str .= implode($fullAddress)  . ';';
            // $str .= $value->address . ';';
            $str .= str_replace('-','',$value->email) . ';';
            Storage::append('data.csv', $str);
        }
    }


}
