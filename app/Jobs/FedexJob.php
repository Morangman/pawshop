<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Cart;
use App\Mail\CartMail;
use App\Order;
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
            ->each(static function ($order) use ($fedExIntegration) {
                $response = $fedExIntegration->track(
                    (int) $order->getAttribute('tracking_number')
                )->toArray();

                dd($response);

                if ($fedExIntegration->isValidResponse($response)) {
                    $originalStatus = $order->getAttribute('fedex_status');
                    $statusFromResponse = Arr::get(
                        $response,
                        'CompletedTrackDetails.0.TrackDetails.0.StatusDetail.Code'
                    );

                    if ($statusFromResponse
                        && $statusFromResponse !== $originalStatus
                        && Order::isValidStatus($statusFromResponse)
                    ) {
                        $order->update(['fedex_status' => $statusFromResponse]);
                    }
                }
            });
    }
}
