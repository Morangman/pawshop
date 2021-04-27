<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Cart;
use App\Jobs\CartJob;
use App\Jobs\FedexJob;
use App\Mail\CartMail;
use App\Order;
use App\Services\FedexService;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class Fedex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fedex:track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order status by FedEx track number';

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
        FedexJob::dispatch();
    }
}
