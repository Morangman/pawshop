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
 * Class EvgaCheckCardsAvailableJob
 *
 * @package App\Jobs
 */
class EvgaCheckCardsAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $client = new Client(HttpClient::create(
            array(
            'headers' => array(
                'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Upgrade-Insecure-Requests' => '1',
                'Save-Data' => 'on',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
            ),
        )));

        $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');

        $evgaBasicPath = 'https://www.evga.com';

        $evgaPoint = "https://www.evga.com/products/productlist.aspx?type=0&family=GeForce+30+Series+Family&chipset=RTX+3090";

        $evgaCrawler = $client->request('GET', $evgaPoint);

        try {
            $evgaCrawler->filter('.list-item')->each(function (Crawler $nodeCrawler) use ($evgaBasicPath, $client) {
                $available = $nodeCrawler->filter('.btnBigAddCart')->count() === 1;

                $skuId = $nodeCrawler->filter('.pl-list-pn')->text();

                $card = DB::table('cards')
                    ->where('site', '=', 'evga')
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
                            ->where('site', '=', 'evga')
                            ->where('sku_id', '=', $skuId)
                            ->update([
                                'available' => $available
                            ]);
                    }
                } else {
                    if ((bool) $card->available !== $available) {
                        DB::table('cards')
                            ->where('site', '=', 'evga')
                            ->where('sku_id', '=', $skuId)
                            ->update([
                                'available' => $available
                            ]);
                    }
                }
            });
        } catch (\Exception $e) {}
    }
}
