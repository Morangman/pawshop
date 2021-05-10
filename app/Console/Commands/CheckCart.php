<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\CartJob;
use Illuminate\Console\Command;

class CheckCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:cart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending notification for users with cart';

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
        CartJob::dispatch();
    }
}
