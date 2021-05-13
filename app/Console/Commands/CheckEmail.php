<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\CheckEmailJob;
use Illuminate\Console\Command;

class CheckEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check email for new messages';

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
        CheckEmailJob::dispatch();
    }
}
