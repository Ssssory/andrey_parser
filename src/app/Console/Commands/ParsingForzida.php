<?php

namespace App\Console\Commands;

use App\Classes\Forzida;
use App\Enums\Sources;
use App\Jobs\TempForzidaJob;
use App\Models\Url;
use Illuminate\Console\Command;

class ParsingForzida extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parsing:forzida';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start parsing forzida';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $forzida = new Forzida();

        $forzida->getUrlsFromSitemap();
        
        $urls = Url::where('status', 'new')->where('source', Sources::Forzida->name)->get();
        if ($urls->isEmpty()) {
            $this->info('No new urls');
            return;
        }
        $this->info('Start parsing');

        foreach ($urls as $url) {
            $url->status = 'in progress';
            $url->save();
            TempForzidaJob::dispatch($url);
            sleep(5);
        }

        $urls = Url::where('status', 'new')->where('source', Sources::Forzida->name)->get();
        if ($urls->isEmpty()) {
            $this->info('Finish');
        }
        $this->handle();
    }
}
