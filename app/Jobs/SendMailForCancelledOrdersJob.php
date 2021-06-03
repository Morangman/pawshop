<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Mail\OrderCancelledMail;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendMailForCancelledOrdersJob
 *
 * @package App\Jobs
 */
class SendMailForCancelledOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach (Order::query()->where('ordered_status', '=', Order::STATUS_CANCELLED)->get() as $order) {
            try {
                Mail::to($order->getAttribute('user_email'))
                    ->send(new OrderCancelledMail(
                        array_merge(
                            $order->toArray(),
                            [
                                'user_name' => $order->user->getAttribute('name'),
                            ]
                        )
                    ));
            } catch (\Exception $e) {}
        }
    }
}
