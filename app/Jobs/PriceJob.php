<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Cart;
use App\Mail\CartMail;
use App\Price;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class PriceJob
 *
 * @package App\Jobs
 */
class PriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach (Price::query()->get() as $price) {
            $price->update([
                'price' => number_format((float) $price->getAttribute('price') * 0.83334, 2, '.', ''),
                'custom_price' => $price->getAttribute('custom_price') ? number_format((float) $price->getAttribute('custom_price') * 0.83334, 2, '.', '') : null,
            ]);
        }
    }
}
