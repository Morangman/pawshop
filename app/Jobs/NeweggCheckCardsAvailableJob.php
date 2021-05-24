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

        $pageCount = 23;

        for ($page = 1; $page <= $pageCount; $page++) {
            $neweggPoint = "https://www.newegg.com/p/pl?Submit=Property&Subcategory=48&N=100007709%20601361654%20601357247%20601327179%20601341631%20601321492%20601359422%20601350459%20601332298%20601346498%20601273503%20601296396%20601296377%20600536049%20600565061%20601359415%20601357248%20601341616%20601321493%20601362404%20601359957%20601350460%20601349617%20601329884%20601205646%20601305993%20601294835%20601296397%20600582123%20600536050%20601357250%20601330988%20601273511%20601202919%20601194948%20601341484%20601350068%20601359427%20601341621%20601321556&IsPowerSearch=1&page=$page";

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
                                ->where('site', '=', 'newegg')
                                ->where('sku_id', '=', $skuId)
                                ->update([
                                    'available' => $available
                                ]);
                        }
                    } else {
                        if ((bool) $card->available !== $available) {
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
