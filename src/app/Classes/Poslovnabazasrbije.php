<?php

namespace App\Classes;

use App\Enums\Sources;
use App\Models\DirtyData;
use App\Models\Url;
use Illuminate\Support\Facades\Storage;

final class Poslovnabazasrbije extends ParserAbstract
{
    protected $prefixStorage = 'poslovnabazasrbije';
    private string $hash;
    private string $category = '2';

    function __construct()
    {
        $this->baseUrl = Sources::getUrl(Sources::Poslovnabazasrbije);
        parent::__construct();
    }

    public function getCompaniesFromPage($html)
    {
        $blocks = $html->find('.box-info');
        $result = [];
        foreach ($blocks as $block) {
            $currentBlock = [];
            $currentBlock['title'] = trim($block->find('.activitie')[0]->text());
            $currentBlock['location'] = trim($block->find('.location')[0]->text());
            $currentBlock['email'] = trim(preg_replace('/[\r\n\s+]+/', '', $block->find('.read-info')[0]->text()));
            $result[] = $currentBlock;
        }
        foreach ($result as $oneBlock) {
            if (!DirtyData::where('hash', $this->hash)->where('email', $oneBlock['email'])->where('name', $oneBlock['title'])->exists()) {
                $data = new DirtyData();
                $data->source = Sources::Poslovnabazasrbije->name;
                $data->hash = $this->hash;
                $data->name = $oneBlock['title'];
                $data->email = $oneBlock['email'];
                $data->address = $oneBlock['location'];
                $data->save();
            }
        }
        return $result;
    }

    public function getPagination($html)
    {
        $pagination = $html->find('.pagination')[0];
        $links = $pagination->find('a');
        $result = [];
        foreach ($links as $a) {
            $link = $a->getAttribute('href');
            if (!Url::where('url', $link)->exists()){
                $url = new Url();
                $url->source = Sources::Poslovnabazasrbije->name;
                $url->url = $link;
                $url->category = $this->category;
                $url->save();
            }
            $result[] = $link;
        }
        return $result;
    }

    public function getHtml($path): string
    {
        $hash = md5($path);
        $this->hash = $hash;

        if (!config('app.debug')) {
            $response = $this->sendRequest($path);
            return $response->getBody()->getContents();
        }
        
        $hash = $this->getCachePath($hash);
        if (Storage::disk('local')->exists($hash)) {
            return Storage::disk('local')->get($hash);
        } else {
            $response = $this->sendRequest($path);
            $html = $response->getBody()->getContents();
        }
        Storage::disk('local')->put($hash, $html);
        return $html;
    }

    private function getCachePath($hash): string
    {
        return $this->prefixStorage . '/' . $hash;
    }
}