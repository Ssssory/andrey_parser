<?php

namespace App\Http\Controllers;

use App\Classes\Poslovna\Poslovnabazasrbije;
use App\Enums\Sources;
use App\Models\DirtyData;
use DiDom\Document;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Exception;


class PoslovnaController extends BaseController
{
    public function category(Request $request)
    {

        $source = Sources::Poslovnabazasrbije;

        $url = $request->get('url');

        $poslovnabazasrbije = new Poslovnabazasrbije();

        $html = $poslovnabazasrbije->getHtml('');
        $document = new Document($html);
        $list = $poslovnabazasrbije->getHomepage($document);

        if (!empty($url)) {
            foreach ($list as $value) {
                if ($value['catigory'] == $url) {
                    $poslovnabazasrbije->saveUrl($value['link']);
                }
            }
        }

        return view('pages.poslovna.category', [
            'title' => $source->name,
            'list' => $list,
        ]);
    }

    public function one(Request $request)
    {
        $source = Sources::Poslovnabazasrbije;
        // $url = $request->get('url');
        $url = 'Search?id=1&page=1&category=13';
        $poslovnabazasrbije = new Poslovnabazasrbije();
        $poslovnabazasrbije->getCategoryId($url);
        $html = $poslovnabazasrbije->getHtml($url);
        $document = new Document($html);
        $list = $poslovnabazasrbije->getDataFromPageWithoutSave($document);
        dd($list);
        // $pagination = $poslovnabazasrbije->getPagination($document);
        return view('pages.poslovna.one', [
            'title' => $source->name,
            // 'list' => $list,
            // 'pagination' => $pagination,
        ]);
    }
}