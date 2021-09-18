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
use Illuminate\Support\Arr;

/**
 * Class CheckIphoneAvailableJob
 *
 * @package App\Jobs
 */
class CheckIphoneAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $path = 'https://www.apple.com/shop/fulfillment-messages?pl=true&mt=compact&cppart=UNLOCKED/US&parts.0=MLKV3LL/A&searchNearby=true&store=R031';

        $data = file_get_contents($path);

        if ($data) {
            $arr = json_decode($data, true);

            $stores = Arr::get($arr, 'body.content.pickupMessage.stores', []);

            foreach ($stores as $store) {
                $storeNumber = Arr::get($store, 'storeNumber', null);

                $storeName = Arr::get($store, 'storeName', null);

                if ($storeName === 'La Encantada') {
                    continue;
                }

                $item = Arr::get($store, 'partsAvailability.MLKV3LL/A', null);

                $lat = Arr::get($store, 'storelatitude', null);
                $lon = Arr::get($store, 'storelongitude', null);

                $check = DB::table('cards')->where('sku_id', '=', $storeNumber)->first();

                $pickup = Arr::get($item, 'pickupDisplay', null);

                if ($pickup && $pickup === 'available') {
                    if (!$check) {
                        $url = "https://www.apple.com/shop/bag";

                        DB::table('cards')->insert([
                            'sku_id' => $storeNumber,
                            'name' => $storeName,
                            'href' => $url,
                            'site' => 'apple.com',
                            'image' => '',
                            'price' => '',
                            'available' => 1,
                        ]);
    
                        $botApiToken = env('TELEGRAM_BOT_API_CHECKER');
                        $chat_id = env('TELEGRAM_CHAT_ID_CHECKER');
    
                        $keyboard = [
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => "Show",
                                        'url' => $url,
                                    ]
                                ]
                            ]
                        ];
                
                        $data = [
                            'chat_id' => $chat_id,
                            'text' => "Iphone 13 pro is available on $storeName",
                            'reply_markup' => json_encode($keyboard)
                        ];
                
                        file_get_contents("https://api.telegram.org/bot{$botApiToken}/sendMessage?" . http_build_query($data));    
                    }
                } else {
                    if ($check) {
                        DB::table('cards')->where('sku_id', '=', $storeNumber)->delete();
                    }
                }

            }
        }
    }
}
