<?php 

namespace App\Http\Controllers;

use App\Classes\Halooglasi;
use App\Classes\Telegram\Client;
use App\Classes\Telegram\Message;
use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SergiX44\Nutgram\Telegram\Types\Input\InputMediaPhoto;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

class TestController extends Controller
{

    const CHAT_ID = -925022442;

    private $bot;

    public function index(Request $request)
    {
        if ($url = $request->input('url', null)) {
            $haluglasi = new Halooglasi();
            $html = $haluglasi->getHtml($url);
            $document = new Document($html);
            $result = $haluglasi->getStateFromPage($document);
            dd($result);
        }
        return view('pages.test', [
            'url' => $url
        ]);
    }

    function sendMessage()
    {
        $this->bot = (new Client)->getClient();

        $photos = [
            'https://img.halooglasi.com/slike/oglasi/Thumbs/230120/m/obilicev-venac-4-0-5425642744935-71802940458.jpg',
            'https://img.halooglasi.com/slike/oglasi/Thumbs/230120/m/obilicev-venac-4-0-5425642744935-71802940457.jpg'
        ];

        $message = new Message();
        $message->setImages($photos);
        $message->price = '1000 EUR';
        $message->deposit = true;
        $message->type = 'rent';
        $message->square = '100 m2';

        $this->sendRentMessage($message);

        return new Response('OK', 200);
    }

}