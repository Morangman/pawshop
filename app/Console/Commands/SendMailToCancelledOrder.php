<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\SendMailForCancelledOrdersJob;
use Illuminate\Console\Command;

class SendMailToCancelledOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancell:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail notifications for cancelled orders';

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
        SendMailForCancelledOrdersJob::dispatch();
    }
}
