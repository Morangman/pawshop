<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Category;
use App\Order;
use Illuminate\Console\Command;

class RecountStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recount:stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update statistics for categories';

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
        Category::query()->whereNotNull('subcategory_id')->get()->map(function($category) {
            Category::query()->whereKey($category->getAttribute('subcategory_id'))->update(['view_count' => 0]);
        });

        Category::query()->whereNotNull('subcategory_id')->get()->map(function($category) {
            Category::query()->whereKey($category->getAttribute('subcategory_id'))->increment('view_count', (int) $category->getAttribute('view_count'));
        });
    }
}
