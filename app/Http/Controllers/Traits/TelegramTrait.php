<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Traits;

use CURLFile;

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
        $botApiToken = env('TELEGRAM_BOT_API');

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
            'chat_id' => env('TELEGRAM_CHAT_ID'),
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
        string $title = 'New offer',
        string $url = '',
        string $imageUrl = '',
        string $pruductName = '',
        float $price = 0
    ): void
    {
        $botApiToken = env('TELEGRAM_BOT_API');
        $chat_id = env('TELEGRAM_CHAT_ID');

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

        $kboard = json_encode($keyboard);

        $image = explode(PHP_EOL, $imageUrl);
        $image = urlencode(trim($image[0]));
        $text = urlencode("$title\n$pruductName\n$price$");
        $url = "https://api.telegram.org/bot{$botApiToken}/sendPhoto?chat_id={$chat_id}&photo={$image}&caption={$text}&reply_markup={$kboard}";
        file_get_contents($url);
    }
}