<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Category;
use App\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManagerStatic as Image;

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

        // foreach (Category::query()->whereNull('custom_text')->get() as $category) {
        //     $slug = $category->getAttribute('slug');

        //     $slug = str_replace('sell-', '', $slug);

        //     $slug = str_replace('sell_', '', $slug);

        //     $slug = str_replace('_', '-', $slug);

        //     $category->update([
        //         'slug' => $slug
        //     ]);
        // }

        // foreach (Category::query()->where('subcategory_id', '=', 28)->get() as $phone) {
        //     $name = $phone->getAttribute('name');

        //     $name = 'Samsung ' . $name;

        //     $slug = $phone->getAttribute('slug');

        //     $slug = 'samsung-' . $slug;

        //     $phone->update([
        //         'name' => $name,
        //         'slug' => $slug,
        //     ]);
        // }

        foreach (Category::query()->get() as $category) {
            $extension = getimagesize($category->getAttribute('image'));

            $name =  str_replace(' ', '_', strtolower($category->getAttribute('name')));

            if ($extension) {
                $path = '/media/' . Category::MEDIA_COLLECTION_CATEGORY . '_' . str_replace('/', '_', $name) . '.webp';

                $fullPath = Config::get('app.url') . $path;
    
                Image::make($category->getAttribute('image'))->encode('webp', 90)->resize(260, 260)->save(public_path($path));
    
                $category->update([
                    'compressed_image' => $fullPath,
                ]);
            } else {
                $category->update([
                    'compressed_image' => $category->getAttribute('image'),
                ]);
            }
        }
    }
}
