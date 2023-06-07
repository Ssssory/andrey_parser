<?php

namespace App\Http\Controllers;

use App\Classes\Poslovnabazasrbije;
use App\Enums\Sources;
use App\Models\DirtyData;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DiDom\Document;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36';

    public function index(Request $request)
    {
        $path = 'Search?id=1&page=1&category=2';
        $poslovnabazasrbije = new Poslovnabazasrbije();

        $html = $poslovnabazasrbije->getHtml($path);
        $document = new Document($html);
        // $result = $this->getCompaniesFromPage($document);
        // $result = $poslovnabazasrbije->getCompaniesFromPage($document);
        // $pagination = $this->getPagination($document);
        // dump($pagination);
        // return view('pages.index');
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


}
