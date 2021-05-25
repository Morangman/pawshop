<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PullGraphicCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull graphics cards available command';

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
        $clearTable = false;
        
        $bestbuy = false;
        $newegg = false;

        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        if ($clearTable) {
            Schema::disableForeignKeyConstraints();

            DB::table('cards')->truncate();
    
            Schema::enableForeignKeyConstraints();
        }

        if ($bestbuy) {
            $bestBuyBasicPath = 'https://www.bestbuy.com';

            $this->line('Start parsing graphics cards from www.bestbuy.com...');

            $pageCount = 1;

            while ($pageCount) {
                $bestBuyPoint = "https://www.bestbuy.com/site/computer-cards-components/video-graphics-cards/abcat0507002.c?cp=$pageCount&id=abcat0507002&intl=nosplash&qp=gpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203060%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203060%20Ti%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203070%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203080%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203090";

                $bestBuyCrawler = $client->request('GET', $bestBuyPoint);

                $scuItems = $bestBuyCrawler->filter('.sku-item');

                if ($scuItems->count() === 0) {
                    $pageCount = null;
                } else {
                    $bestBuyCrawler->filter('.sku-item')->each(function (Crawler $nodeCrawler) use ($bestBuyBasicPath, $client) {
                        $name = $nodeCrawler->filter('.sku-header')->count() !== 0 ? $nodeCrawler->filter('.sku-header')->text() : $nodeCrawler->filter('.heading-5 a')->text();

                        DB::table('cards')->insert([
                            'image' => $nodeCrawler->filter('.product-image')->count() !== 0 ? $nodeCrawler->filter('.product-image')->attr('src') : $nodeCrawler->filter('.picture-wrapper img')->attr('src'),
                            'href' => $nodeCrawler->filter('.sku-header a')->count() !== 0 ? $bestBuyBasicPath . $nodeCrawler->filter('.sku-header a')->attr('href') : $bestBuyBasicPath . $nodeCrawler->filter('.heading-5 a')->attr('href'),
                            'name' => $name,
                            'sku_id' => (int) $nodeCrawler->filter('.add-to-cart-button')->attr('data-sku-id'),
                            'price' => trim($nodeCrawler->filter('.priceView-customer-price span')->text(), '$'),
                            'available' => $nodeCrawler->filter('.add-to-cart-button')->text() === "Add to Cart",
                            'site' => 'bestbuy',
                        ]);

                        $this->line("$name was parsed...");
                    });

                    $this->line("on $pageCount page...");
        
                    $pageCount++;
                }
            }

            $this->line('BestBuy graphics cards was parsed...');
        }

        if ($newegg) {
            $neweggBasicPath = 'https://www.newegg.com';

            $this->line('Start parsing graphics cards from www.newegg.com...');

            $pageCount = 3;

            for ($page = 1; $page <= $pageCount; $page++) {
                $neweggPoint = "https://www.newegg.com/p/pl?Submit=Property&Subcategory=48&N=100007709%20601357282&IsPowerSearch=1&page=$page";

                $neweggCrawler = $client->request('GET', $neweggPoint);

                try {
                    $neweggCrawler->filter('.item-cell')->each(function (Crawler $nodeCrawler) use ($neweggBasicPath, $client) {
                        $name = $nodeCrawler->filter('.item-title')->text();

                        $price = 0;

                        if ($nodeCrawler->filter('.item-button-area button')->count() > 0) {
                            if ($nodeCrawler->filter('.price-current strong')->count() !== 0) {
                                $price = $nodeCrawler->filter('.price-current strong')->text() . $nodeCrawler->filter('.price-current sup')->text();
                            }
        
                            if ($nodeCrawler->filter('.price-was-data')->count() !== 0) {
                                $price = trim($nodeCrawler->filter('.price-was-data')->text(), '$');
                            }
        
                            if ($nodeCrawler->filter('.item-buying-choices-price')->count() !== 0) {
                                $price = trim($nodeCrawler->filter('.item-buying-choices-price')->text(), '$');
                            }
        
                            DB::table('cards')->insert([
                                'image' => $nodeCrawler->filter('.item-img img')->attr('src'),
                                'href' => $nodeCrawler->filter('.item-title')->attr('href'),
                                'name' => $name,
                                'sku_id' => $nodeCrawler->filter('.item-stock')->attr('id'),
                                'price' => $price,
                                'available' => $nodeCrawler->filter('.item-button-area button')->text() === 'Add to cart',
                                'site' => 'newegg',
                            ]);
                        }

                        $this->line("$name was parsed...");
                    });
                } catch (\Exception $e) {
                    dd($e);
                }

                $this->line("on $page page...");
            }

            $this->line('Newegg graphics cards was parsed...');
        }
    }
}
