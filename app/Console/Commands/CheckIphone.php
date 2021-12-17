<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\CheckIphoneAvailableJob;
use Illuminate\Console\Command;

class CheckIphone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:iphone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending notification';

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
    }
}
