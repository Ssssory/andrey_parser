<?php

namespace App\Http\Controllers;

use App\Classes\Clutch;
use App\Classes\Poslovnabazasrbije;
use App\Enums\Sources;
use App\Models\DirtyData;
use App\Models\Url;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DiDom\Document;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Scalar\MagicConst\Dir;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $links = Url::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();
        $totalLinks = $this->prepareCounts($links);

        $dirtyData = DirtyData::select(DB::raw('source, count(source) as total'))->groupBy('source')->get();
        $totalData = $this->prepareCounts($dirtyData);

        $activeLinks = Url::select(DB::raw('source, count(source) as total'))->where('status', 'in progress')->groupBy('source')->get();
        $activeLinksData = $this->prepareCounts($activeLinks);
        // dump($activeLinks);
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'sources' => Sources::cases(),
            'totalLinksCount' => Url::count(),
            'totalLinks' => $totalLinks,
            'totalData' => $totalData,
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

    public function test(Request $request)
    {
        $path = 'web-developers';
        $clutch = new Clutch();

        $html = $clutch->getHtml($path);
        $document = new Document($html);
        // $result = $clutch->getCompaniesFromPage($document);
        $pagination = $clutch->getPagination($document);
        dump($pagination);
        // return view('pages.index');
    }

    public function saveCsv()
    {
        // $data = DirtyData::limit(10)->get();
        $data = DirtyData::all();
        Storage::disk('public')->put('test.csv', '');
        // $str = '';
        foreach ($data as $key => $value) {
            $str = '';
            $str .= $value->name . ';';
            $str .= $value->address . ';';
            $str .= $value->email . ';';
            Storage::append('filename.txt', $str);
        }
    }


}
