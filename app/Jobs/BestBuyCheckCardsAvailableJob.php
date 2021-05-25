<?php

declare(strict_types = 1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;

/**
 * Class BestBuyCheckCardsAvailableJob
 *
 * @package App\Jobs
 */
class BestBuyCheckCardsAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $bestBuyBasicPath = 'https://www.bestbuy.com';

        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        $pageCount = 1;

        while ($pageCount) {
            $bestBuyPoint = "https://www.bestbuy.com/site/computer-cards-components/video-graphics-cards/abcat0507002.c?cp=$pageCount&id=abcat0507002&intl=nosplash&qp=gpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203060%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203060%20Ti%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203070%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203080%5Egpusv_facet%3DGraphics%20Processing%20Unit%20(GPU)~NVIDIA%20GeForce%20RTX%203090";

            $bestBuyCrawler= $client->request('GET', $bestBuyPoint);

            $scuItems = $bestBuyCrawler->filter('.sku-item');

            if ($scuItems->count() === 0) {
                $pageCount = null;
            } else {
                $bestBuyCrawler->filter('.sku-item')->each(function (Crawler $nodeCrawler) use ($bestBuyBasicPath, $client) {
                    $available = $nodeCrawler->filter('.add-to-cart-button')->text() === "Add to Cart";

                    $skuId = $nodeCrawler->filter('.add-to-cart-button')->attr('data-sku-id');

                    $card = DB::table('cards')
                        ->where('site', '=', 'bestbuy')
                        ->where('sku_id', '=', $skuId)
                        ->first();
                    
                    if ($available) {
                        if ((bool) $card->available !== $available) {
                            $botApiToken = env('TELEGRAM_BOT_API_CHECKER');
                            $chat_id = env('TELEGRAM_CHAT_ID_CHECKER');
                    
                            $keyboard = [
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => "Show",
                                            'url' =>  $card->href,
                                        ]
                                    ]
                                ]
                            ];
                    
                            $kboard = json_encode($keyboard);
                    
                            $image = explode(PHP_EOL, $card->image);
                            $image = urlencode(trim($image[0]));
                            $text = urlencode("$card->name\nShop - $card->site\n$card->price$");
                            $url = "https://api.telegram.org/bot{$botApiToken}/sendPhoto?chat_id={$chat_id}&photo={$image}&caption={$text}&reply_markup={$kboard}";
                            file_get_contents($url);


                            DB::table('cards')
                                ->where('site', '=', 'bestbuy')
                                ->where('sku_id', '=', $skuId)
                                ->update([
                                    'available' => $available
                                ]);
                        }
                    } else {
                        if ((bool) $card->available !== $available) {
                            DB::table('cards')
                                ->where('site', '=', 'bestbuy')
                                ->where('sku_id', '=', $skuId)
                                ->update([
                                    'available' => $available
                                ]);
                        }
                    }
                });
    
                $pageCount++;
            }
        }
    }
}
