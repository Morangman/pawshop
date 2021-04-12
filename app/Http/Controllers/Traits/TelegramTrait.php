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
}