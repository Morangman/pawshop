<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Mail\FedexLabelMail;
use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class FedexLabelJob
 *
 * @package App\Jobs
 */
class FedexLabelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $orders = Order::query()->whereNull('tracking_number')->where('ordered_status', '=', Order::STATUS_NEW)->get();

        foreach ($orders as $order) {
            $user = User::query()->whereKey($order->getAttribute('user_id'))->first();

            if ($user && $user->getAttribute('mail_subscription') && $order->getAttribute('send_ctn') !== 2) {
                try {
                    Mail::to($user->getAttribute('email'))
                        ->send(new FedexLabelMail(
                            [
                                'orders' => $order->getAttribute('orders'),
                                'user_name' => $user->getAttribute('name'),
                                'uuid' => $order->getAttribute('uuid'),
                            ]
                        ));

                    $order->increment('send_ctn', 1);
                } catch (\Exception $e) {}
            }
        }
    }
}
