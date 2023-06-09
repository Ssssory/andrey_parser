<?php

namespace App\Classes;

use App\Enums\Sources;
use App\Models\DirtyData;
use App\Models\DirtyStateData;
use App\Models\DirtyStateParametersData;
use App\Models\Url;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

use Orchestra\Parser\Xml\Facade as XmlParser;

final class Forzida extends ParserAbstract
{
    const FRESH_DAYS = 7;
    protected $prefixStorage = 'forzida';
    private string $hash;
    private string $uri;

    function __construct()
    {
        $this->baseUrl = Sources::getUrl(Sources::Forzida);
        parent::__construct();
    }

    public function getStateFromPage($html)
    {
        $name = $html->find('h1')[0]->text();
        $images = $html->find('.gallery-image');
        $gallery = [];
        foreach ($images as $image) {
            $gallery[] = $image->src;
        }

        $attributes = $html->find('.ng-star-inserted>.label');
        $property = [];
        foreach ($attributes as $attr) {
            $label = $attr->text();
            $arrtValue = $attr->nextSibling()->text();
            $property[] = ['label' => $label, 'value' => $arrtValue];
        }
        $description = $html->find('.ed-description')[0]->text();

        $address = $html->find('app-place-info')[0]->text();

        if (!DirtyStateData::where('hash', $this->hash)->exists()) {
            $data = new DirtyStateData();
            $data->source = Sources::Forzida->value;
            $data->hash = $this->hash;
            $data->url = $this->uri;
            $data->name = $name;
            $data->images = implode(',', $gallery);
            $data->description = $description;
            $data->address = $address;
            $data->save();

            foreach ($property as $item) {
                new DirtyStateParametersData([
                    'state_id' => $data->id,
                    'property' => $item['label'],
                    'value' => $item['value'],
                ]);
            }
        }
    }

    public function getHtml($path): string
    {
        $hash = md5($path);
        $this->hash = $hash;
        $this->uri = $path;
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

    function getUrlsFromSitemap()
    {
        $robots = $this->sendRequest(Sources::getUrl(Sources::Forzida) . 'robots.txt');
        $robotsText = $robots->getBody()->getContents();
        $robotsArray = explode("\n", $robotsText);
        $sitemapUrl = '';
        foreach ($robotsArray as $row) {
            if (preg_match('/Sitemap:/', $row)) {
                $sitemapUrl = explode(' ', $row)[1];
                break;
            }
        }
        if (empty($sitemapUrl)) {
            throw new Exception("no sitemap", 404);
        }
        // $sitemapUrl = 'https://u4z97b7314cb8348.4zida.rs/sitemap.xml';
        $siteXmlRequest = $this->sendRequest($sitemapUrl);
        $siteXmlRequestBody = $siteXmlRequest->getBody()->getContents();
        $xml = simplexml_load_string($siteXmlRequestBody); 
        $urls = [];
        foreach ($xml as $url) {
            if (preg_match('/rent/', $url->loc)) {
                $urls[] = $url->loc;
            }
        }

        foreach ($urls as $item) {
            $this->savePageUrl($item[0]);
        }

        dd($urls);
    }

    private function savePageUrl(string $xmlUrl) 
    {
        $siteXmlRequest = $this->sendRequest($xmlUrl);
        $siteXmlRequestBody = $siteXmlRequest->getBody()->getContents();
        $xml = simplexml_load_string($siteXmlRequestBody);
        foreach ($xml as $item) {
            if (Carbon::parse($item->lastmod) > Carbon::now()->subDays(self::FRESH_DAYS)) {
                if (!Url::where('url', $item->loc)->exists()) {
                    $url = new Url();
                    $url->source = Sources::Forzida->name;
                    $url->url = $item->loc;
                    $url->category = "rent";
                    $url->save();
                }
            }
        }
    }
}
