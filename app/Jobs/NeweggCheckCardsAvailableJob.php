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
 * Class NeweggCheckCardsAvailableJob
 *
 * @package App\Jobs
 */
class NeweggCheckCardsAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $client = new Client(HttpClient::create(['timeout' => 30 * 30 * 24]));

        $neweggBasicPath = 'https://www.newegg.com/';

        $pageCount = 3;

        for ($page = 1; $page <= $pageCount; $page++) {
            $neweggPoint = "https://www.newegg.com/p/pl?Submit=Property&Subcategory=48&N=100007709%20601357282&IsPowerSearch=1&page=$page";

            $neweggCrawler = $client->request('GET', $neweggPoint);

            try {
                $neweggCrawler->filter('.item-cell')->each(function (Crawler $nodeCrawler) use ($neweggBasicPath, $client) {

                    $available = $nodeCrawler->filter('.item-button-area button')->text() === 'Add to cart';

                    $skuId = $nodeCrawler->filter('.item-stock')->attr('id');

                    $card = DB::table('cards')
                        ->where('site', '=', 'newegg')
                        ->where('sku_id', '=', $skuId)
                        ->first();
                    
                    if ($available) {
                        if ($card && (bool) $card->available !== $available) {
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
                                ->where('site', '=', 'newegg')
                                ->where('sku_id', '=', $skuId)
                                ->update([
                                    'available' => $available
                                ]);
                        }
                    } else {
                        if ($card && (bool) $card->available !== $available) {
                            DB::table('cards')
                                ->where('site', '=', 'newegg')
                                ->where('sku_id', '=', $skuId)
                                ->update([
                                    'available' => $available
                                ]);
                        }
                    }
                });
            } catch (\Exception $e) { continue; }
        }
    }
}
