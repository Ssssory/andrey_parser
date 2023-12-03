<?php 

namespace App\Classes\Car;

use App\Classes\ParserAbstract;
use App\Enums\Sources;
use App\Models\DirtyCarData;
use App\Models\DirtyCarParametersData;
use App\Models\Url;
use DiDom\Document;
use DiDom\Element;
use DiDom\Node;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

final class Polovniautomobili extends ParserAbstract
{
    protected string $prefixStorage = 'polovniautomobili';
    private string $hash;
    private string $uri;
    
    function __construct()
    {
        $this->baseUrl = Sources::getUrl(Sources::Polovniautomobili);
        parent::__construct();
    }

    public function getHtml(string $path): string
    {
        $hash = md5($path);
        $this->hash = $hash;
        $this->uri = $path;

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

    private function getCachePath(string  $hash): string
    {
        return $this->prefixStorage . '/' . $hash;
    }

    public function getDataFromPage(Document $html): void
    {
        /** @var Node $node */
        $node = $html->first('body > div.details.js-ad-details-page > div.uk-container.uk-container-center.body > div.table.js-tutorial-all > div > h1');
        if ($node != null){
            $h1 = $node->firstChild()->text();
        }else{
            return;
        }
        $h1 = preg_replace('/[\t,\n]*/', '', $h1);
        $photos = $html->find('.cS-hidden > li');
        $photosCollection = new Collection($photos);
        $photos = $photosCollection->map(static function ($item) {
            /** @var Element $item */
            return $item->attr('data-src');
        });
        $gallery = $photos->toArray();

        $listProperty = $html->find('.divider');
        $dividerCollection = new Collection($listProperty);
        $property = $dividerCollection->mapWithKeys(static function ($item) {
            /** @var Element $item */
            $list = $item->child(1)->find('.uk-width-1-2');
            $current = [];
            foreach ($list as $property) {
                /** @var Element $property */
                $current[] = $property->text();
            }
            return [$current[0] => $current[1]];
        });

        $descriptionNode = $html->first('.description-wrapper');
        if ($descriptionNode != null) {
            /** @var Element $descriptionNode */
            $description = $descriptionNode->text();
        }else{
            $description = '';
        }
        
        if (!empty($description)) {
            $description = preg_replace('/[\t,\n]*/', '', $description);
        }

        $priceNode = $html->first('body > div.details.js-ad-details-page > div.uk-container.uk-container-center.body > div.table.js-tutorial-all > aside > div.uk-grid > div > div > div > div > span');
        if ($priceNode != null) {
            /** @var Element $priceNode */
            $price = $priceNode->text();
            $price = empty($price) ? '' : $price;
        }else{
            $price = '';
        }

        if (!DirtyCarData::where('hash', $this->hash)->exists()) {
            $data = new DirtyCarData();
            $data->source = Sources::Polovniautomobili->value;
            $data->hash = $this->hash;
            $data->url = $this->uri;
            $data->price = $price;
            $data->name = $h1;
            $data->images = implode(',', $gallery);
            $data->description = $description;
            $data->save();

            foreach ($property as $key => $value) {
                DirtyCarParametersData::create([
                    'car_id' => $data->id,
                    'property' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    function getUrlsFromFillter(): void
    {
        $brands = [
            "audi",
            "bmw",
            "chevrolet",
            "citroen",
            "dacia",
            "dodge",
            "fiat",
            "ford",
            "honda",
            "hyundai",
            "infiniti", 
            "jeep",
            "kia",
            "lancia",
            "mazda",
            "mINI",
            "mitsubishi",
            "nissan",
            "opel",
            "peugeot",
            "porsche",
            "renault",
            "seat",
            "smart",
            "subaru",
            "suzuki",
            "toyota",
            "volkswagen",
            "volvo",
            "alfa-romeo",
            "land-rover",
            "mercedes-benz",
            "skoda",

        ];

        foreach ($brands as $brand) {            
            $this->parseFiltredUrl($brand);
        }
    }

    private function parseFiltredUrl(string $brand, int $page = 1): void
    {
        if ($page > 3) {
            return;
        }
        $url = $this->urlConstructor($brand, $page);
        // echo $url . PHP_EOL;
        $carList = $this->getHtml(Sources::getUrl(Sources::Polovniautomobili) . $url);

        $html = new Document($carList);

        /** @var Element $node */
        $node = $html->first('#search-results');
        $links = $node->find('h2 > a');

        if (empty($links)) {
            return;
        }
        $this->savePageUrl($links);

        $page++;
        // echo $page . PHP_EOL;
        // if ($html->first('#search-results')->find('.js-pagination-next')) {
        //     unset($html);
        //     $this->parseFiltredUrl($brand, $page);
        // }
        unset($html,$page);
    }

    private function urlConstructor(string $brand, int $page=0 ) : string 
    {
        $parts = [
            'price' => '&price_from=1000&price_to=30000',
            'year' => '&year_from=2001&year_to=2021', // -1 от текущего
            'date_limit' => '&date_limit=1',

        ];
        if ($page > 1) {
            $parts['page'] = '&page=' . $page;
        }else{
            $parts['page'] = '';
        }

        $brand = '?brand=' . $brand;

        return 'auto-oglasi/pretraga' . $brand . $parts['page'] . $parts['price'] . $parts['year'] . $parts['date_limit'];
    }

    private function savePageUrl(array $urls): void
    {
        foreach ($urls as $item) {
            $uri = substr(Sources::getUrl(Sources::Polovniautomobili),0,-1) . $item->getAttribute('href');
            if (!Url::where('url', $uri)->exists()) {
                $url = new Url();
                $url->source = Sources::Polovniautomobili->name;
                $url->url = $uri;
                $url->category = "car";
                $url->save();
            }
        }
    }
}
