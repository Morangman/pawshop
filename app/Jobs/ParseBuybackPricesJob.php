<?php

declare(strict_types = 1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Illuminate\Support\Facades\DB;

/**
 * Class ParseBuybackPricesJob
 *
 * @package App\Jobs
 */
class ParseBuybackPricesJob implements ShouldQueue
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

        $html = $client->request('GET', 'https://www.mobilesentrix.com/lcd-buy-back/');

        $result = [];

        $results = $html->filter('.bstr')->each(function (Crawler $html) {
            $title = $html->filter('.d_model')->text();
            $price = $html->filter('li')->eq(1)->text();

            return [
                'model' => $title,
                'price' => (float) str_replace('$', '', $price),
            ];
        });

        foreach ($results as $result) {
            //make history
            DB::connection('rapidrefurb')->table('product_models')->where('buyback_code', '=', $result['model'])->update([
                'buyback_price' => $result['price']
            ]);
        }

    }
}
