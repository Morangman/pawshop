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
        // $path = 'https://www.apple.com/shop/fulfillment-messages?pl=true&mt=compact&cppart=UNLOCKED/US&parts.0=MLKV3LL/A&searchNearby=true&store=R031';

        // $path = 'https://www.apple.com/shop/fulfillment-messages?store=R031&little=false&mt=regular&parts.0=MLKN3LL/A&searchNearby=true&cppart=UNLOCKED/US';

        $keys = [
            'MQ0E3LL/A', //14 pro deep purple 128gb
            'MQ063LL/A', //14 pro gold 128gb
            'MQ003LL/A', //14 pro silver 128gb
            'MPXT3LL/A', //14 pro space black 128gb
            'MQ1D3LL/A', //14 pro deep purple 256gb
            'MQ273LL/A', //14 pro deep purple 512gb
            'MQ303LL/A', //14 pro deep purple 1tb
            'MQ163LL/A', //14 pro gold 256gb
            'MQ213LL/A', //14 pro gold 512gb
            'MQ2T3LL/A', //14 pro gold 1tb
            'MQ0X3LL/A', //14 pro silver 256gb
            'MQ1U3LL/A', //14 pro silver 512gb
            'MQ2L3LL/A', //14 pro silver 1tb
            'MQ0N3LL/A', //14 pro black 256gb
            'MQ1K3LL/A', //14 pro black 512gb
            'MQ2E3LL/A', //14 pro black 1tb
            'MQ8R3LL/A', //14 pro MAX deep purple 128gb
            'MQ8Q3LL/A', //14 pro MAX gold 128gb
            'MQ8P3LL/A', //14 pro MAX silver 128gb
            'MQ8N3LL/A', //14 pro MAX black 128gb
            'MQ8W3LL/A', //14 pro MAX deep purple 256gb
            'MQ8V3LL/A', //14 pro MAX gold 256gb
            'MQ8U3LL/A', //14 pro MAX silver 256gb
            'MQ8T3LL/A', //14 pro MAX black 256gb
            'MQ913LL/A', //14 pro MAX deep purple 512gb
            'MQ903LL/A', //14 pro MAX gold 512gb
            'MQ8Y3LL/A', //14 pro MAX silver 512gb
            'MQ8X3LL/A', //14 pro MAX black 512gb
            'MQ953LL/A', //14 pro MAX deep purple 1tb
            'MQ943LL/A', //14 pro MAX gold 1tb
            'MQ933LL/A', //14 pro MAX silver 1tb
            'MQ923LL/A', //14 pro MAX black 1tb
        ];

        foreach ($keys as $key) {
            echo $key . PHP_EOL;
            $data = null;

            try {
                $path = "https://www.apple.com/shop/fulfillment-messages?store=R031&little=false&parts.0=$key&cppart=UNLOCKED/US&mts.0=regular&mts.1=sticky&fts=true";

                $data = file_get_contents($path);
        
            } catch (\Exception $e) {
                sleep(20);

                $path = "https://www.apple.com/shop/fulfillment-messages?store=R031&little=false&parts.0=$key&cppart=UNLOCKED/US&mts.0=regular&mts.1=sticky&fts=true";

                $data = file_get_contents($path);
            }

            if ($data) {
                $arr = json_decode($data, true);
    
                $stores = Arr::get($arr, 'body.content.pickupMessage.stores', []);
    
                foreach ($stores as $store) {
                    $storeNumber = Arr::get($store, 'storeNumber', null);
    
                    $storeName = Arr::get($store, 'storeName', null);

                    echo $storeName . PHP_EOL;
    
                    if ($storeName === 'La Encantada') {
                        continue;
                    }
    
                    $item = Arr::get($store, "partsAvailability.$key", null);
    
                    $lat = Arr::get($store, 'storelatitude', null);
                    $lon = Arr::get($store, 'storelongitude', null);
    
                    $check = DB::table('cards')->where('sku_id', '=', $storeNumber)->first();
    
                    $pickup = Arr::get($item, 'pickupDisplay', null);
    
                    if ($pickup && $pickup === 'available') {
                        echo 'available' . PHP_EOL;

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
                                'text' => "iPhone 14 Pro $key is available on $storeName",
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
}
