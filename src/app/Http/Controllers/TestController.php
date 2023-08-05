<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DiDom\Document;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // $html = file_get_contents('https://www.polovniautomobili.com/auto-oglasi/22036024/audi-q5-20tdi-s-line-90hk-m?attp=p3_pv0_pc1_pl1_plv0');
        // Storage::disk('public')->put('test.html', $html);
        $html = Storage::disk('public')->get('test.html');
        $ext = new Document($html);
        $h1 = $ext->first('body > div.details.js-ad-details-page > div.uk-container.uk-container-center.body > div.table.js-tutorial-all > div > h1')->text();
        $h1 = preg_replace('/[\t,\n]*/', '', $h1);
        $price = $ext->first('body > div.details.js-ad-details-page > div.uk-container.uk-container-center.body > div.table.js-tutorial-all > aside > div.uk-grid > div > div > div > div > span')->text();
        $listProperty = $ext->find('.divider');
        $dividerCollection = new Collection($listProperty);
        $listProperty = $dividerCollection->mapWithKeys(static function($item){
            $list = $item->child(1)->find('.uk-width-1-2');
            $current = [];
            foreach ($list as $property) {
                $current[] = $property->text();
            }
            return [$current[0] => $current[1]];
        });

        $photos = $ext->find('.cS-hidden > li');
        $photosCollection = new Collection($photos);
        $photos = $photosCollection->map(static function($item){
            return $item->attr('data-src');
        });
        
        dd(config('app.debug'));
        return view('pages.test', [
            'url' => '',
            'photos' => $photos
        ]);
    }

    

}