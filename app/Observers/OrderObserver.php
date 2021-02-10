<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Notifications\OrderNotification;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Notification;

/**
 * Class OrderObserver
 *
 * @package App\Observers
 */
class OrderObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param \App\Order $order
     *
     * @return void
     *
     * @throws \Exception
     */
    public function created(Order $order): void
    {
        Notification::send(
            User::query()->scopes(['notifiableUsers'])->get(),
            new OrderNotification($order->getKey())
        );
    }
}
