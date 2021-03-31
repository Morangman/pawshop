<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Mail\OrderChangeConfirmationMail;
use App\Notifications\OrderNotification;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

/**
 * Class OrderObserver
 *
 * @package App\Observers
 */
class OrderObserver
{
    /**
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

    /**
     * @param \App\Order $order
     *
     * @return void
     */
    public function updated(Order $order): void
    {
        $newValues = [];
        $oldValues = [];
        
        foreach ($order->getAttributes() as $key => $value) {
            if (!$order->originalIsEquivalent($key, $value)) {
                $oldValues = json_decode($order->getOriginal($key), true);
                $newValues = json_decode($value, true);

                $diff = [];

                if (isset($newValues['order'])) {
                    foreach ($newValues['order'] as $keyOrder => $newOrder) {
                        foreach ($newOrder['steps'] as $keyStep => $step) {
                            if ($step['value'] !== $oldValues['order'][$keyOrder]['steps'][$keyStep]['value']) {
                                $newValues['changed'] = Order::STATUS_CHANGED;
                                $newValues['confirmed'] = Order::STATUS_NOT_CONFIRMED;

                                $diff[] = [
                                    'step' => $step,
                                    'order_id' => $newOrder['id'],
                                    'device' => $newOrder['device'],
                                ];
                            }
                        }
                    }
                }

                if ($diff !== []) {
                    try {
                        Mail::to($order->getAttribute('user_email'))
                            ->send(new OrderChangeConfirmationMail(
                                $order->toArray()
                            ));
                    } catch (\Exception $e) {}
                }

                $order->unsetEventDispatcher();

                $order->update(['orders' => $newValues]);
            }
        }
    }
}
