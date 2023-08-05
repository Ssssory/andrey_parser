<?php

namespace App\Jobs;

use App\Classes\Car\Polovniautomobili;
use App\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DiDom\Document;

class TempPolovniautomobiliJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Url $url;

    /**
     * Create a new job instance.
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $polovniautomobili = new Polovniautomobili();
        $html = $polovniautomobili->getHtml($this->url->url);
        $document = new Document($html);
        $polovniautomobili->getDataFromPage($document);

        $this->url->status = 'done';
        $this->url->complete = true;
        $this->url->save();
    }
}
