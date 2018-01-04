<?php

namespace App\Console\Commands;

use App\Helpers\PriceHelper;
use Illuminate\Console\Command;

class FetchPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:prices {currency?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all prices and store them in application cache. Optionally provide a currency id (e.g. "bitcoin") to only fetch that price.';

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
        PriceHelper::fetch($this->argument('currency'));
    }
}
