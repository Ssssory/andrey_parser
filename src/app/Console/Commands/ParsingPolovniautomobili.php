<?php

namespace App\Console\Commands;

use App\Classes\Car\Polovniautomobili;
use App\Enums\Sources;
use App\Jobs\TempPolovniautomobiliJob;
use App\Models\Url;
use Illuminate\Console\Command;

class ParsingPolovniautomobili extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parsing:polovniautomobili';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start parsing polovniautomobili';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo 'parce urls fillter' . PHP_EOL;
        $polovniautomobili = new Polovniautomobili();

        $polovniautomobili->getUrlsFromFillter();
        echo 'parce urls' . PHP_EOL;
        $urls = Url::where('status', 'new')->where('source', Sources::Polovniautomobili->name)->get();
        if ($urls->isEmpty()) {
            $this->info('No new urls');
            return;
        }
        $this->info('Start parsing');

        foreach ($urls as $url) {
            $url->status = 'in progress';
            $url->save();
            TempPolovniautomobiliJob::dispatch($url);
            sleep(5);
        }

        $this->info('Finish');

    }
}
