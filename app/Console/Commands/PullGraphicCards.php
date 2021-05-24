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
        
        $bestbuy = true;
        $newegg = true;

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
                $bestBuyPoint = "https://www.bestbuy.com/site/computer-cards-components/video-graphics-cards/abcat0507002.c?cp=$pageCount&id=abcat0507002&intl=nosplash";

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

            $pageCount = 23;

            for ($page = 1; $page <= $pageCount; $page++) {
                $neweggPoint = "https://www.newegg.com/p/pl?Submit=Property&Subcategory=48&N=100007709%20601361654%20601357247%20601327179%20601341631%20601321492%20601359422%20601350459%20601332298%20601346498%20601273503%20601296396%20601296377%20600536049%20600565061%20601359415%20601357248%20601341616%20601321493%20601362404%20601359957%20601350460%20601349617%20601329884%20601205646%20601305993%20601294835%20601296397%20600582123%20600536050%20601357250%20601330988%20601273511%20601202919%20601194948%20601341484%20601350068%20601359427%20601341621%20601321556&IsPowerSearch=1&page=$page";

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
