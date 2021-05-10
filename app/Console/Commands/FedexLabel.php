<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\FedexLabelJob;
use Illuminate\Console\Command;

class FedexLabel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fedex:label';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending notification for users who not create FedEx label';

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
        FedexLabelJob::dispatch();
    }
}
