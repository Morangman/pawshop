<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\ParseBuybackPricesJob;
use Illuminate\Console\Command;

class ParseBuybackPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:buyback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse prices for buyback';

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
     * @return void
     */
    public function handle(): void
    {
        ParseBuybackPricesJob::dispatch();
    }
}
