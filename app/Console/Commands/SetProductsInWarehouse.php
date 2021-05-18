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
        Warehouse::query()->truncate();

        $orders = Order::query()->where('ordered_status', '=', Order::STATUS_PAID)->get();

        foreach($orders as $order) {
            $ordersProducts = $order->getAttribute('orders')['order'];

            $summCtn = 0;

            foreach($ordersProducts as $orderProduct) {
                $summCtn += (int) $orderProduct['ctn'];
            }

            foreach($ordersProducts as $orderProduct) {
                $ctn = (int) $orderProduct['ctn'];

                $expShipping = 20 / $summCtn;

                $imeiArr = isset($orderProduct['imei']) ? explode(',', ',' . $orderProduct['imei']) : [];

                unset($imeiArr[0]);

                $serialNumberArr = isset($orderProduct['serial']) ? explode(',', ',' . $orderProduct['serial']) : [];

                unset($serialNumberArr[0]);

                for ($i = 1; $i <= $ctn; $i++) {
                    $orderTotalSumm = isset($orderProduct['total']) ? $orderProduct['total'] / $ctn : 5;

                    if ($order->getAttribute('exp_service')) {
                        (float) $orderTotalSumm -= $expShipping;
                    }

                    if ($order->getAttribute('insurance')) {
                        (float) $orderTotalSumm -= ((float) $orderTotalSumm  * 1)/100;
                    }

                    Warehouse::query()->create([
                        'category_id' => $orderProduct['device']['id'],
                        'order_id' => $order->getKey(),
                        'status' => Warehouse::STATUS_PAID,
                        'product_name' => $orderProduct['device']['name'],
                        'imei' => isset($imeiArr[$i]) ? $imeiArr[$i] : null,
                        'serial_number' => isset($serialNumberArr[$i]) ? $serialNumberArr[$i] : null,
                        'price' => isset($orderProduct['total']) ? $orderProduct['total'] / $ctn : 5,
                        'clear_price' => $orderTotalSumm,
                        'is_locked' => isset($orderProduct['is_locked']),
                        'delivery_price' => isset($orderProduct['delivery_price']) ? $orderProduct['delivery_price'] : 0,
                        'repair_price' => isset($orderProduct['repairs_price']) ? $orderProduct['repairs_price'] : 0,
                        'sell_price' => isset($orderProduct['sell_price']) ? $orderProduct['sell_price'] : 0,
                        'steps' => $orderProduct['steps'],
                        'exp_service' => $order->getAttribute('exp_service') ? true : false,
                        'insurance' => $order->getAttribute('insurance') ? true : false,
                    ]);
                }
            }
        }
    }
}
