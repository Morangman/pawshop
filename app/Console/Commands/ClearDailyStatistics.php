<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\ClearDailyStatisticsJob;
use Illuminate\Console\Command;

class ClearDailyStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear daily statistics table';

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
        ClearDailyStatisticsJob::dispatch();
    }
}
