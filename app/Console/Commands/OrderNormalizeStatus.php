<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Category;
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
        //Order::query()->whereNotNull('tracking_number')->whereNull('fedex_status')->update(['fedex_status' => Order::STATUS_SHIPMENT_CREATED]);

        foreach (Category::query()->whereNull('custom_text')->get() as $category) {
            $slug = $category->getAttribute('slug');

            $slug = str_replace('sell-', '', $slug);

            $slug = str_replace('sell_', '', $slug);

            $slug = str_replace('_', '-', $slug);

            $category->update([
                'slug' => $slug
            ]);
        }

        foreach (Category::query()->where('subcategory_id', '=', 28)->get() as $phone) {
            $name = $phone->getAttribute('name');

            $name = 'Samsung ' . $name;

            $slug = $phone->getAttribute('slug');

            $slug = 'samsung-' . $slug;

            $phone->update([
                'name' => $name,
                'slug' => $slug,
            ]);
        }
    }
}
