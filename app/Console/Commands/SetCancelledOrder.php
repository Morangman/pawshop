<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\SetCancelOrderJob;
use Illuminate\Console\Command;

class SetCancelledOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set order as cancelled after 10 days period';

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
        SetCancelOrderJob::dispatch();
    }
}
