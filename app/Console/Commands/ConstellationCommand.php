<?php

namespace App\Console\Commands;

use App\Service\CrawlService;
use Goutte\Client;
use Illuminate\Console\Command;

class ConstellationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'constellation:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $crawlService = new CrawlService($client);
        $crawlService->mainCrawl();
        $this->info('done');
    }
}
