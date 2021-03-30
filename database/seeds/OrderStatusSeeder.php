<?php

declare(strict_types = 1);

use App\OrderStatus;
use Illuminate\Database\Seeder;

/**
 * Class OrderStatusSeeder
 */
class OrderStatusSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        OrderStatus::query()->create([
            'name' => 'New',
            'color' => '#008000',
            'order' => 1,
        ]);
    }
}
