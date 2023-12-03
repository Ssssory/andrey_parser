<?php

namespace App\Console\Commands;

use App\Enums\Sources;
use App\Jobs\SourceJob;
use App\Models\Url;
use Illuminate\Console\Command;

class Parsing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parsing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start parsing';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $urls = Url::where('status', 'new')->where('source', Sources::Poslovnabazasrbije->name)->get();
        // $urls = Url::where('status', 'in progress')->where('source', Sources::Poslovnabazasrbije->name)->get();
        // $urls[0]->status = 'new';
        // $urls[0]->save();
        // $urls[0]->delete();
// dd($urls);
        if ($urls->isEmpty()) {
            $this->info('No new urls');
            return;
        }
        $this->info('Start parsing');

        foreach ($urls as $url) {
            $url->status = 'in progress';
            $url->save();
            SourceJob::dispatch($url);
            sleep(5);
        }

        $urls = Url::where('status', 'new')->where('source', Sources::Poslovnabazasrbije->name)->get();
        if ($urls->isEmpty()) {
            $this->info('Finish');
        }
        $this->handle();
            
    }
}
