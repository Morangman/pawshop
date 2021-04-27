<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;

class OrderNormalizeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:norm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order fedex status';

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
        Order::query()->whereNotNull('tracking_number')->whereNull('fedex_status')->update(['fedex_status' => Order::STATUS_SHIPMENT_CREATED]);
    }
}
