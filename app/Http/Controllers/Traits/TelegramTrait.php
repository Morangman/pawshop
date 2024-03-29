<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Traits;

use App\Order;
use CURLFile;
use Illuminate\Support\Facades\Config;

/**
 * Trait TelegramTrait
 *
 * @package App\Http\Controllers\Traits
 */
trait TelegramTrait
{
    /**
     * @param string $title
     * @param string $url
     *
     * @return void
     */
    protected function sendMessage(string $title, string $url): void
    {
        $botApiToken = Config::get('services.telegram.token');

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
            'chat_id' => Config::get('services.telegram.chat'),
            'text' => "$title",
            'reply_markup' => json_encode($keyboard)
        ];

        file_get_contents("https://api.telegram.org/bot{$botApiToken}/sendMessage?" . http_build_query($data));
    }

    /**
     * @param string $title
     * @param string $url
     * @param string $image
     * @param string $pruductName
     * @param float|int $price
     *
     * @return void
     */
    protected function sendOffer(
        string $title = 'New order',
        int $id,
        string $url = '',
        string $imageUrl = '',
        string $pruductName = '',
        float $price = 0
    ): void
    {
        $botApiToken = Config::get('services.telegram.token');
        $chat_id = Config::get('services.telegram.chat');

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

        $order = Order::query()->whereKey($id)->first();

        $name = $order->getAttribute('address')['name'];

        $kboard = json_encode($keyboard);

        if (str_contains($imageUrl, '.svg')) {
            $imageUrl = 'https://rapid-recycle.com/client/images/category_sell_graphics_card_(gpu).png';
        }

        $image = explode(PHP_EOL, $imageUrl);
        $image = urlencode(trim($image[0]));
        $text = urlencode("$title №$id\n$name\n$pruductName\n$price$");
        $url = "https://api.telegram.org/bot{$botApiToken}/sendPhoto?chat_id={$chat_id}&photo={$image}&caption={$text}&reply_markup={$kboard}";
        file_get_contents($url);
    }
}