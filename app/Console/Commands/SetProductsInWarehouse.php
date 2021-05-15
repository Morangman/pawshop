<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\CartJob;
use App\Order;
use App\Warehouse;
use Illuminate\Console\Command;

class SetProductsInWarehouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warehouse:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save products in warehouse with status payed from orders';

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
        $orders = Order::query()->where('ordered_status', '=', Order::STATUS_PAID)->get();

        foreach($orders as $order) {
            $ordersProducts = $order->getAttribute('orders')['order'];

            foreach($ordersProducts as $orderProduct) {
                $ctn = (int) $orderProduct['ctn'];

                $priceFromTotal = $orderProduct['total'] / $ctn;

                dd($priceFromTotal);

                for ($i = 0; $i <= $ctn; $i++) {
                    Warehouse::query()->create([
                        'category_id' => $orderProduct['device']['id'],
                        'order_id' => $order->getKey(),
                        'status' => Warehouse::STATUS_PAID,
                        'product_name' => $orderProduct['device']['name'],
                        'imei' => '',
                        'serial_number' => '',
                        'price' => '',
                        'clear_price' => '',
                        'is_locked' => isset($orderProduct['is_locked']),
                        'delivery_price' => '',
                        'repair_price' => '',
                        'sell_price' => '',
                        'steps' => $orderProduct['steps'],
                    ]);
                }
            }
        }
    }
}
