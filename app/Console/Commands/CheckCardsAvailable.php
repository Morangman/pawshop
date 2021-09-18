<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\BestBuyCheckCardsAvailableJob;
use App\Jobs\EvgaCheckCardsAvailableJob;
use App\Jobs\NeweggCheckCardsAvailableJob;
use App\Jobs\CheckIphoneAvailableJob;
use Illuminate\Console\Command;

class CheckCardsAvailable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check graphics cards available command';

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
        CheckIphoneAvailableJob::dispatch();
        // BestBuyCheckCardsAvailableJob::dispatch();
        // NeweggCheckCardsAvailableJob::dispatch();
        // EvgaCheckCardsAvailableJob::dispatch();
    }
}
