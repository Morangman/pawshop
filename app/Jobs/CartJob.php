<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Cart;
use App\Mail\CartMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class CartJob
 *
 * @package App\Jobs
 */
class CartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $carts = Cart::all();

        foreach ($carts as $cart) {
            $user = User::query()->whereKey($cart->getAttribute('user_id'))->first();

            if ($user) {
                try {
                    Mail::to($user->getAttribute('email'))
                        ->send(new CartMail(
                            [
                                'orders' => $cart->getAttribute('orders'),
                                'user_name' => $user->getAttribute('name'),
                            ]
                        ));
                } catch (\Exception $e) {}
            }
        }
    }
}
