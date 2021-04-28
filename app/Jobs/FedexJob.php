<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Cart;
use App\Mail\CartMail;
use App\Order;
use App\OrderStatus;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Services\FedexService;
use Illuminate\Support\Arr;

/**
 * Class FedexJob
 *
 * @package App\Jobs
 */
class FedexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $fedExIntegration = new FedexService();

        Order::query()
            ->whereNotNull('tracking_number')
            ->where('fedex_status', '!=', Order::STATUS_DELIVERED)
            ->where('ordered_status', '!=', Order::STATUS_RECEIVED)
            ->where('ordered_status', '!=', Order::STATUS_CANCELLED)
            ->each(static function ($order) use ($fedExIntegration) {
                $response = $fedExIntegration->track(
                    (int) $order->getAttribute('tracking_number')
                )->toArray();

                if ($fedExIntegration->isValidResponse($response)) {
                    $originalStatus = $order->getAttribute('fedex_status');
                    $statusFromResponse = Arr::get(
                        $response,
                        'CompletedTrackDetails.0.TrackDetails.0.StatusDetail.Code'
                    );

                    if ($statusFromResponse
                        && Order::isValidStatus($statusFromResponse)
                    ) {
                        $realSts = $statusFromResponse;

                        if ($statusFromResponse === Order::STATUS_IN_TRANSIT ||
                            $statusFromResponse === Order::STATUS_ON_FEDEX_VEHICLE ||
                            $statusFromResponse === Order::STATUS_ARRIVED ||
                            $statusFromResponse === Order::STATUS_ON_FEDEX_FACILITY ||
                            $statusFromResponse === Order::STATUS_AT_FEDEX_FACILITY ||
                            $statusFromResponse === Order::STATUS_AT_SORT_FACILITY ||
                            $statusFromResponse === Order::STATUS_ON_ORIGIN_FACILITY ||
                            $statusFromResponse === Order::STATUS_LEFT_ORIGIN ||
                            $statusFromResponse === Order::STATUS_CLEARANCE_DELAY
                        ) {
                            $realSts = Order::STATUS_IN_TRANSIT;
                        }

                        $status = OrderStatus::query()->where('fedex_status', '=', $realSts)->first();

                        $order->unsetEventDispatcher();

                        $order->update([
                            'ordered_status' => $status ? $status->getKey() : Order::STATUS_NEW,
                            'fedex_status' => $statusFromResponse
                        ]);
                    }
                }
            });
    }
}
